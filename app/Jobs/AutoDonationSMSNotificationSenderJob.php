<?php

namespace App\Jobs;

use App\Models\Ahed\Needy;
use App\Models\Ahed\OnlineTransaction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AutoDonationSMSNotificationSenderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private array $transactions)
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
        $transactions = OnlineTransaction::whereIn('id', collect($this->transactions)->pluck('id'))
            ->with(['giver', 'needy'])->get();
        foreach ($transactions as $transaction) {
            Log::info(
                'Message',
                ['number' => $transaction->giver->phone_number, 'message' => 'Thank you ' . $transaction->giver->name .
                    ' For Donating Amount: ' . $transaction->amount .
                    ' For Case: ' . $transaction->needy->name .
                    '\n' .
                    'You can find the case details from here: ' . $transaction->needy->url]
            );
        }
    }
}
