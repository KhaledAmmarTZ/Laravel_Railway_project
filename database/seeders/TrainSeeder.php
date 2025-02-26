<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TrainSeeder extends Seeder
{
    public function run()
    {
        $trainNames = [
            'Padma Express', 'Sonar Bangla Express', 'Suborno Express', 'Jamuna Express', 
            'Mahananda Express', 'Dhaka Mail', 'Kishoreganj Express', 'Chattala Express', 
            'Chittagong Express', 'Khulna Express', 'Sylhet Express', 'Rajshahi Express', 
            'Rajendra Express', 'Madhumati Express', 'Bhawal Express', 'Dhalai Express',
            'Faridpur Express', 'Manikganj Express', 'Madaripur Express', 'Gopalganj Express', 
            'Rupsha Express', 'Brahmaputra Express', 'Ganges Express', 'Dholai Express', 
            'Tungabhadra Express', 'North Bengal Express', 'South Bengal Express', 
            'Moulvibazar Express', 'Comilla Express', 'Sundarbans Express', 'Bagerhat Express'
        ];

        $stations = ['Dhaka', 'Faridpur', 'Gazipur', 'Gopalganj', 'Jamalpur', 
                     'Kishoreganj', 'Madaripur', 'Manikganj', 'Chittagong'];

        $compartmentTypes = ['AC', 'Sleeper', 'First Class', 'Economy', 'Business', 'Women', 'Handicap'];

        for ($i = 1; $i <= 30; $i++) {
            $compartmentNumber = rand(1, 7); 
            $updownNumber = rand(1, 3);   

            $trainId = DB::table('train')->insertGetId([
                'trainname' => $trainNames[array_rand($trainNames)], 
                'compartmentnumber' => $compartmentNumber,
                'updownnumber' => $updownNumber,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            for ($j = 1; $j <= $compartmentNumber; $j++) {
                $compartmentType = $compartmentTypes[array_rand($compartmentTypes)]; 

                DB::table('traincompartments')->insert([
                    'trainid' => $trainId,
                    'seatnumber' => $j,
                    'compartmentname' => 'Compartment ' . $j,
                    'compartmenttype' => $compartmentType,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            for ($k = 1; $k <= $updownNumber; $k++) {
                $tsource = $stations[array_rand($stations)]; 
                $tdestination = $stations[array_rand($stations)]; 

                while ($tsource === $tdestination) {
                    $tdestination = $stations[array_rand($stations)];
                }

                DB::table('trainupdowns')->insert([
                    'trainid' => $trainId,
                    'tarrtime' => now()->addHours(rand(1, 12))->format('H:i:s'),
                    'tdeptime' => now()->addHours(rand(13, 24))->format('H:i:s'),
                    'tarrdate' => now()->addDays(rand(1, 7))->format('Y-m-d'),
                    'tdepdate' => now()->addDays(rand(1, 7))->format('Y-m-d'),
                    'tsource' => $tsource,
                    'tdestination' => $tdestination,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
