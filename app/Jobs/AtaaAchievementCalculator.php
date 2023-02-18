<?php

namespace App\Jobs;

use App\ConverterModels\OwnerType;
use App\Models\Ataa\AtaaAchievement;
use App\Models\Ataa\AtaaPrize;
use App\Models\Ataa\FoodSharingMarker;
use App\Models\BaseUserModel;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class AtaaAchievementCalculator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $user;
    protected $foodSharingMarker;
    protected $method;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BaseUserModel $user, FoodSharingMarker $foodSharingMarker, String $method)
    {
        $this->user = $user;
        $this->foodSharingMarker = $foodSharingMarker;
        $this->method = $method;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $userAchievement = AtaaAchievement::calculateThenGet($this->user, $this->foodSharingMarker, $this->method);
        if(OwnerType::$value[class_basename($this->user)] == 1){
            $prizesNotAcquired = AtaaPrize::notAcquiredByUser($this->user);
            //If Empty -> no previous prizes || user acquired all -> Auto Create new one with higher value
            if ($prizesNotAcquired->isEmpty()) {
                $highestAcquiredPrize = AtaaPrize::orderBy('level', 'DESC')->where('active', '=', 1)->get()->first();
                //There is previous prize then create one with higher level
                if ($highestAcquiredPrize) {
                    AtaaPrize::seedHigherPrize($highestAcquiredPrize);
                }
                //There is no previous prize
                else {
                    AtaaPrize::initiatePrize($this->method);
                }
            }
            //There is prizes exists & Not acquired By User
            else {
                foreach ($prizesNotAcquired as $prize) {
                    if ($prize['required_markers_collected'] <= $userAchievement['markers_collected'] && $prize['required_markers_posted'] <= $userAchievement['markers_posted']) {
                        $prize->winners()->attach(
                            $this->user->id
                        );
                    } else {
                        //TODO: Maybe show the user what's left for his next milestone
                    }
                }
            }
        }
    }
}
