<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $stations = ['Dhaka', 'Faridpur', 'Gazipur', 'Gopalganj', 'Jamalpur', 
        'Kishoreganj', 'Madaripur', 'Manikganj', 'Chittagong', 'Khulna',
        'Kamalpur','Airport','Sylhet','Rajshahi'];

        foreach ($stations as $city) {

            DB::table('station')->insert([
                'stationname' => $city . ' Railway Station',
                'city' => $city,
            ]);
        }
    }
}