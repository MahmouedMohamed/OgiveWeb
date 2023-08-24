<?php

namespace App\Jobs;

use App\Models\Ataa\AtaaPrize;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AtaaPrizeDeactivatorJob implements ShouldQueue
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
        //Today => 19
        //To => 18
        //today >= To
        // 19 >= 18
        return AtaaPrize::where('to', '<=', Carbon::now())
            ->where('active', '=', 1)
            ->update(['active' => false]);
    }
}
