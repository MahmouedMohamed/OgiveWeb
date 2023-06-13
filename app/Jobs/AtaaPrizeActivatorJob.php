<?php

namespace App\Jobs;

use App\Models\Ataa\AtaaPrize;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AtaaPrizeActivatorJob implements ShouldQueue
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
        //Today => 17
        //From => 16
        //To => 18
        // from <= today <= To
        // 16 <= 17 < 18
        return AtaaPrize::where('from', '<=', Carbon::now())
            ->where('to', '>', Carbon::now())
            ->where('active', '=', 0)
            ->update(['active' => true]);
    }
}
