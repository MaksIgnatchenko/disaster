<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 06.12.2018
 *
 */

namespace App\Jobs;

use App\Services\DisasterHandler\DisasterApiHandler;
use App\Services\DisasterHandler\Exceptions\HiszRsoeApiConnectErrorException;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ParseDisasterApi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $apiHandler;
    private $cacheTtl;

    public function handle()
    {
        $cidAbove = Cache::tags(['disaster_meta'])->get('cid_above', 1);
        $this->apiHandler = new DisasterApiHandler($cidAbove);
        $this->cacheTtl = config('app_settings.cache_api_result_ttl');

        try {
            $this->apiHandler->request();
        } catch (HiszRsoeApiConnectErrorException $e) {
            Log::error($e->getMessage());
        }
        $disasters = $this->apiHandler->getResult();
        $yesterday = Carbon::yesterday();
        $resultSet = [];
        $disastersMetaData = [];
        foreach($disasters as $disaster) {
            $disasterDate = Carbon::parse($disaster['event_date']);
            if ($disasterDate->greaterThanOrEqualTo($yesterday)) {
                $resultSet[$disaster['cid']] = $disaster;
                $cidAbove = ($disaster['cid'] > $cidAbove) ? $disaster['cid'] : $cidAbove;
                $disastersMetaData['keys'][] = $disaster['cid'];
            }
        }
//        Log::alert(print_r($disasters, true));
//        $disastersMetaData['cid_above'] = $cidAbove ?? 1;
//        Log::alert($disastersMetaData['cid_above']);
//        Log::info(print_r($disastersMetaData, true));
        Cache::tags(['disaster_meta'])->flush();
        Cache::tags(['disaster_meta'])->putMany($disastersMetaData, $this->cacheTtl);
        Cache::tags(['disaster'])->flush();
        Cache::tags(['disaster'])->putMany($resultSet, $this->cacheTtl);
    }
}