<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 06.12.2018
 *
 */

namespace App\Jobs;

use App\Disaster;
use App\Services\DisasterHandler\DisasterApiHandler;
use App\Services\DisasterHandler\Exceptions\HiszRsoeApiConnectErrorException;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ParseDisasterApi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $apiHandler;

    public function handle()
    {
        $cidAbove = DB::table('disasters')->max('cid');
        $this->apiHandler = new DisasterApiHandler($cidAbove);
        try {
            $this->apiHandler->request();
        } catch (HiszRsoeApiConnectErrorException $e) {
            Log::error($e->getMessage());
        }
        $response = $this->apiHandler->getResult();

        $today = Carbon::today();
        $disasters = [];
        foreach ($response as $item) {
            $disasterDate = Carbon::parse($item['event_date']);
            if ($disasterDate->greaterThanOrEqualTo($today)) {
                $disasters[] = [
                    'cid' => $item['cid'],
                    'event_date' => $item['event_date'],
                    'category_code' => $item['category_code'],
                    'category' => $item['category'],
                    'country' => $item['country'],
                    'area_range_definition' => $item['area_range_definition'],
                    'description' => $item['description'],
                ];
            }
            Log::alert(print_r($disasters, true));
        DB::table('disasters')->delete();
        Disaster::insert($disasters);
        }
    }
}