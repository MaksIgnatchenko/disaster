<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 06.12.2018
 *
 */

namespace App\Jobs;

use App\Helpers\DisasterCategories;
use App\Services\DisasterHandler\DisasterHandlerInterface;
use App\Services\DisasterHandler\Exceptions\DisasterApiConnectErrorException;
use App\Services\DisasterHandler\Exceptions\DisasterApiResponseErrorException;
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
    private $disasterModelName;
    private $disasterModel;

    /**
     * Create a new job instance.
     *
     * @param DisasterHandlerInterface $apiHandler
     * @param string $disasterModelName
     */
    public function __construct(DisasterHandlerInterface $apiHandler, string $disasterModelName)
    {
        $this->apiHandler = $apiHandler;
        $this->disasterModelName = $disasterModelName;
    }

    public function handle()
    {
        $this->disasterModel = new $this->disasterModelName();
        $minCid = $this->disasterModel->max('cid');
        $this->apiHandler->setOptions([
            'minCid' => $minCid,
        ]);
        try {
            $this->apiHandler->request();
        } catch (DisasterApiConnectErrorException $e) {
            Log::error($e->getMessage());
            exit;
        }
        try {
            $response = $this->apiHandler->getResult();
        } catch (DisasterApiResponseErrorException $e) {
            Log::alert($e->getMessage());
            exit();
        }

        $yesterday = Carbon::yesterday();
        $disasters = [];
        foreach ($response as $item) {
            $disasterDate = Carbon::parse($item['event_date']);
            if ($disasterDate->greaterThanOrEqualTo($yesterday)
                &&
                in_array($item['category_code'], DisasterCategories::getAvailableCategories())
            ) {
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
        }
        DB::table($this->disasterModel
            ->getTable()
        )->whereDate('event_date', '>=', $yesterday->toDateString())
            ->delete();
        $this->disasterModelName::insert($disasters);
    }
}
