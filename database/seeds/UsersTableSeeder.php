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
        $timezones = [
        	'America/Adak',
			'Asia/Aden',
			'Australia/ACT',
			'Europe/Amsterdam',
			'Europe/Athens',
			'Europe/London',
			'America/New_York',

		];
        $locations = \App\Location::all()->pluck('id');
        for ($i = 0; $i < 100; $i++) {
            $user = \App\User::create([
                'deviceId' => str_random(),
                'pushToken' => str_random(),
                'receipt' => (rand(1,3) > 1) ? base64_encode(str_random(50)) : null,
                'receiptSecret' => (rand(1,3) > 1) ? base64_encode(str_random(50)) : null,
            ]);
            $settings = new \App\Settings();
			$settings->fill([
                'tempUnit' => (rand(1, 2) > 1) ? 'c' : 'f',
				'windSpeedUnit' => (rand(1, 2) > 1) ? 'kph' : 'mph',
				'minTemp' => rand(-20, 0),
				'maxTemp' => rand(20, 40),
				'timezone' => $timezones[array_rand($timezones)],
            ]);
			$user->settings()->save($settings);
			$user->locations()->sync($locations->random());
        }
    }
}
