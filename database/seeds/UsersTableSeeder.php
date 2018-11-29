<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = \Carbon\Carbon::now();
        $dates = [
            $now->addDays(1)->toDateString(),
            $now->addDays(5)->toDateString(),
            $now->addDays(10)->toDateString(),
            $now->subDays(1)->toDateString(),
            $now->subDays(5)->toDateString(),
            $now->subDays(10)->toDateString(),

        ];
        $locations = \App\Location::all();
        for ($i = 0; $i < 100; $i++) {
            $user = \App\User::create([
                'deviceId' => str_random(),
                'pushToken' => str_random(),
                'receipt' => (rand(1,3) > 1) ? base64_encode(str_random(50)) : null,
                'receiptSecret' => (rand(1,3) > 1) ? base64_encode(str_random(50)) : null,
                'expirationDate' => $dates[array_rand($dates)]
            ]);
            $settings = \App\Settings::create([
                ''
            ]);
        }
    }
}
