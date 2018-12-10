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
            ],
            [
                'place' => 'Brussels',
                'country' => 'Belgium',
            ],
            [
                'place' => 'Brasilia',
                'country' => 'Brazil',
            ],
            [
                'place' => 'Ottawa',
                'country' => 'Canada',
            ],
            [
                'place' => 'Beijing',
                'country' => 'China',
            ],
            [
                'place' => 'Zagreb',
                'country' => 'Croatia',
            ],
            [
                'place' => 'Cairo',
                'country' => 'Egypt',
            ],
            [
                'place' => 'Helsinki',
                'country' => 'Finland',
            ],
            [
                'place' => 'Paris',
                'country' => 'France',
            ],
            [
                'place' => 'Kyiv',
                'country' => 'Ukraine',
            ],
        ];
        \App\Location::insert($locations);
    }
}
