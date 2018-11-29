<?php

use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = [
            [
                'place' => 'Kabul',
                'country' => 'Afghanistan',
                'lat' => 34.51666667,
                'long' => 69.183333
            ],
            [
                'place' => 'Brussels',
                'country' => 'Belgium',
                'lat' => 50.83333333,
                'long' => 4.333333
            ],
            [
                'place' => 'Brasilia',
                'country' => 'Brazil',
                'lat' => -15.78333333,
                'long' => -47.916667
            ],
            [
                'place' => 'Ottawa',
                'country' => 'Canada',
                'lat' => 45.41666667,
                'long' => -75.7
            ],
            [
                'place' => 'Beijing',
                'country' => 'China',
                'lat' => 39.91666667,
                'long' => 116.383333
            ],
            [
                'place' => 'Zagreb',
                'country' => 'Croatia',
                'lat' => 45.8,
                'long' => 16
            ],
            [
                'place' => 'Cairo',
                'country' => 'Egypt',
                'lat' => 30.05,
                'long' => 31.25
            ],
            [
                'place' => 'Helsinki',
                'country' => 'Finland',
                'lat' => 60.16666667,
                'long' => 24.933333
            ],
            [
                'place' => 'Paris',
                'country' => 'France',
                'lat' => 48.86666667,
                'long' => 2.333333
            ],
            [
                'place' => 'Kyiv',
                'country' => 'Ukraine',
                'lat' => 50.43333333,
                'long' => 30.516667
            ],
        ];
        \App\Location::insert($locations);
    }
}
