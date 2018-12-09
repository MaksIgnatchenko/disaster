<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 26.11.2018
 *
 */

namespace App\Jobs;

use App\Enums\PushNotificationPlatforms;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendPushJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    private $token;

    /**
     * @var array
     */
    private $messageParams;

    /**
     * @var string
     */
    private $platform;

    /**
     * Create a new job instance.
     *
     * @param string $token
     * @param string $title
     * @param string $message
     */
    public function __construct(string  $token, string $title, string $message)
    {
        $this->token = $token;
        $this->messageParams = [
            'aps' => [
                'alert' => [
                    'title' => $title,
                    'body'  => $message
                ],
                'sound' => 'default'
            ]
        ];
        $this->platform = PushNotificationPlatforms::IOS;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $push = new PushNotification($this->platform);
        $push->setMessage($this->messageParams)
            ->setDevicesToken($this->token)
            ->send();
    }
}
