<?php

namespace App\Jobs;

use App\Location;
use App\Services\WeatherHandler\AerisWeather;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class WeatherApiParse implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->locationModel = app()[Location::class];
        $this->apiHandler = new AerisWeather();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $test = json_encode($this->locationModel->all());
        Log::error($test);
    }
}
