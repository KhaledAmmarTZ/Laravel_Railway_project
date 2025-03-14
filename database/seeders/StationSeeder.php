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
        $cities = ['Dhaka', 'Faridpur', 'Gazipur', 'Gopalganj', 'Jamalpur', 
        'Kishoreganj', 'Madaripur', 'Manikganj', 'Chittagong', 'Khulna',
        'Kamalpur','Airport','Sylhet','Rajshahi'];

        foreach ($cities as $city) {
            $arrivalTime = Carbon::now()->addHours(rand(1, 6));
            $departureTime = (clone $arrivalTime)->addHours(rand(1, 6));

            DB::table('stations')->insert([
                'stationname' => $city . ' Railway Station',
                'artime' => $arrivalTime->format('H:i:s'),
                'deeptime' => $departureTime->format('H:i:s'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
