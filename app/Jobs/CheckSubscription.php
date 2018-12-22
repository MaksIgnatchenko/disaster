<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 06.12.2018
 *
 */

namespace App\Jobs;

use App\Services\ReceiptValidator;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CheckSubscription implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $sharedSecret;

    /**
     * CheckSubscription constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->sharedSecret = config('app_settings.itunes_shared_secret');
    }

    /**
     * Execute the job.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
            $receiptValidator = new ReceiptValidator($this->user->receipt, $this->sharedSecret);
            $expirationTimestamp = $receiptValidator->getExpiresDate();
            $this->user->expirationDate = Carbon::createFromTimestamp($expirationTimestamp)->toDateTimeString();
            $this->user->save();
    }
}
