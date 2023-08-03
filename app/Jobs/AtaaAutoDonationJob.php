<?php

namespace App\Jobs;

use App\Models\Ahed\Needy;
use App\Models\Ahed\OnlineTransaction;
use App\Models\User;
use App\Models\UserAccount;
use App\Models\UserSettings;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AtaaAutoDonationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::beginTransaction();
        DB::enableQueryLog();
        /** @var Collection $usersAvailableForAutoDonation */
        $usersAvailableForAutoDonation = User::whereHas('account', function ($query) {
            return $query->where('balance', '>', 0);
        })->whereHas('settings', function ($query) {
            return $query->where('auto_donate', '=', true)
                ->where(function ($subQuery) {
                    $subQuery->where('latest_auto_donation_time', '<', Carbon::now()->subMinutes(config('settings.ataa_auto_donation_interval_time')))
                        ->orWhere('latest_auto_donation_time', '=', null);
                });
        })->with(['onlinetransactions' => function ($query) {
            $query->where('fulfilled_by_auto_donation', '=', true);
        }])->get()->keyBy('id');
        if ($usersAvailableForAutoDonation->isEmpty()) {
            return;
        }
        /** @var Collection $neediesReadyForDonation */
        $neediesReadyForDonation = Needy::approved()
            ->notSatisfied()
            ->orderBy('severity', 'DESC')->get()->keyBy('id');
        if ($neediesReadyForDonation->isEmpty()) {
            return;
        }

        /** @var array $transactions */
        $transactions = [];
        $userAccounts = [];
        $userSettings = [];
        $needies = [];
        // dd($usersAvailableForAutoDonation);
        foreach ($neediesReadyForDonation as $needyId => $needy) {
            $needies[$needyId] = $needy->getAttributes();
            foreach ($usersAvailableForAutoDonation as $userId => $user) {
                if (
                    $user->settings->auto_donate_on_severity <= $needy->severity
                ) {
                    $alreadyDonatedForNeedy =
                        $user->onlinetransactions->where('needy_id', '=', $needyId)->isNotEmpty();
                    if (! $user->settings->allow_multiple_donation_for_same_needy && $alreadyDonatedForNeedy) {
                        continue;
                    }
                    $affordableAmount = $user->account->balance > $user->settings->max_amount_per_needy_for_auto_donation ?
                        $user->settings->max_amount_per_needy_for_auto_donation : $user->account->balance;

                    $amount = $needy->need - $needy->collected > $affordableAmount ?
                        $affordableAmount : $needy->need - $needy->collected;

                    $transactions[$needyId]['amount'] = ($transactions[$needyId]['amount'] ?? 0) + $amount;
                    $transactions[$needyId]['values'][] = [
                        'id' => Str::uuid(),
                        'giver' => $userId,
                        'needy_id' => $needyId,
                        'amount' => $amount,
                        'remaining' => 0,
                        'fulfilled_by_auto_donation' => true,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];

                    $userAccounts[$user->account->id] = $user->account->getAttributes();
                    $userAccounts[$user->account->id]['balance'] = $user->account->balance - $amount;
                    $userAccounts[$user->account->id]['updated_at'] = Carbon::now();

                    $userSettings[$user->settings->id] = $user->settings->getAttributes();
                    $userSettings[$user->settings->id]['latest_auto_donation_time'] = Carbon::now();
                    $userSettings[$user->settings->id]['updated_at'] = Carbon::now();

                    $usersAvailableForAutoDonation->forget($userId);

                    $needies[$needyId]['collected'] += $amount;

                    //Move to Next Needy if current satisfied
                    if ($needy->collected + ($transactions[$needyId]['amount'] ?? 0) >= $needy->need) {
                        $needies[$needyId]['satisfied'] = true;
                        $needies[$needyId]['updated_at'] = Carbon::now();
                        break;
                    }
                }
            }
        }
        $transactionsToBeAdded = [];
        foreach ($transactions as $transaction) {
            $transactionsToBeAdded = array_merge($transactionsToBeAdded, $transaction['values']);
        }
        OnlineTransaction::insert($transactionsToBeAdded);
        UserAccount::upsert($userAccounts, ['id'], ['balance', 'updated_at']);
        UserSettings::upsert($userSettings, ['id'], ['latest_auto_donation_time', 'updated_at']);
        Needy::upsert($needies, array_keys($needies), ['collected', 'satisfied', 'updated_at']);
        DB::commit();
    }
}
