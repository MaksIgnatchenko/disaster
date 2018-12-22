<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 03.12.2018
 *
 */

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('admins')->insert([
            'email' => 'appus.disaster@gmail.com',
            'password' => Hash::make('admin'),
        ]);
    }
}

