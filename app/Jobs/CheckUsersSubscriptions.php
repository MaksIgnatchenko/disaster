<?php

namespace App\Jobs;

use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CheckUsersSubscriptions implements ShouldQueue
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

    public function handle()
    {
        $users = app()[User::class]->whereNotNull('receipt')
            ->where(function($query) {
                $query->whereDate('expirationDate', '<=', Carbon::today())
                    ->orWhereNull('expirationDate');
            })->get();

        $users->map(function($user) {
            CheckSubscription::dispatch($user);
        });
    }
}
