<?php

namespace App\Jobs;

use App\Location;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GetWeather implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $batchQuantityLimit;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->batchQuantityLimit =
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $locations = Location::all();
        foreach ($locations as $location) {
//            $weather =
        }
    }
}
