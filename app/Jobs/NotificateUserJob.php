<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 06.12.2018
 *
 */

namespace App\Jobs;

use App\Services\WeatherHandler\AerisWeather;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NotificateUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $disasterModelName;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param string $disasterModelName
     */
    public function __construct(User $user, string $disasterModelName)
    {
        $this->user = $user;
        $this->disasterModelName = $disasterModelName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $locations = $this->user->locations;
        $countries = $this->user->locations->pluck('country');
        $disasterCategories = $this->user->settings->disasterCategories;
        $disasters = $this->disasterModelName::whereIn('country', $countries)
            ->whereIn('category_code', $disasterCategories)
            ->get();
        foreach($disasters as $disaster) {
            $title = $disaster->category . ' in ' . $disaster->country . ' ' . $disaster->event_date;
            $message = mb_substr($disaster->description, 0, 100) . ' ...';
            SendPushJob::dispatch(
                $this->user->pushToken,
                $title,
                $message
            );
        }
        ParseWeatherApi::dispatch(new AerisWeather(), $locations, $this->user);
    }
}
