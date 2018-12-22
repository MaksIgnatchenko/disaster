<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 06.12.2018
 *
 */

namespace App\Jobs;

use App\Disaster;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class InitNotificationUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $userModelName;

    /**
     * Create a new job instance.
     *
     * InitNotificationUsersJob constructor.
     * @param string $userModelName
     */
    public function __construct(string $userModelName)
    {
        $this->userModelName = $userModelName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = $this->userModelName::active()->get();
        foreach ($users as $user) {
            NotificateUserJob::dispatch($user, Disaster::class);
        }
    }
}
