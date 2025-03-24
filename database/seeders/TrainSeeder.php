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

        $compartmentTypes = ['AC', 'Sleeper', 'First Class', 'Economy', 'Business', 'Women', 'Handicap'];

        $stations = ['Dhaka', 'Faridpur', 'Gazipur', 'Gopalganj', 'Jamalpur', 
        'Kishoreganj', 'Madaripur', 'Manikganj', 'Chittagong', 'Khulna',
        'Kamalpur','Airport','Sylhet','Rajshahi'];

        $routes = [
            ['Dhaka', 'Faridpur', 'Gazipur', 'Gopalganj', 'Dhaka'],
            ['Chittagong', 'Khulna', 'Rajshahi', 'Sylhet', 'Chittagong'],
            ['Dhaka', 'Gazipur', 'Madaripur', 'Manikganj', 'Dhaka'],
            ['Faridpur', 'Gopalganj', 'Kishoreganj', 'Chittagong', 'Faridpur'],
            ['Khulna', 'Sylhet', 'Rajshahi', 'Dhaka','Rajshahi','Sylhet', 'Khulna'],
            ['Jamalpur', 'Gazipur', 'Madaripur', 'Manikganj', 'Madaripur','Gazipur','Jamalpur'],
            ['Chittagong', 'Sylhet', 'Rajshahi', 'Gopalganj', 'Rajshahi', 'Sylhet', 'Chittagong'],
            ['Faridpur', 'Madaripur', 'Manikganj', 'Kishoreganj','Manikganj','Madaripur', 'Faridpur'],
            ['Rajshahi', 'Dhaka', 'Kamalpur', 'Dhaka', 'Rajshahi'],
            ['Khulna', 'Jamalpur', 'Gopalganj', 'Jamalpur', 'Khulna'],
            ['Airport', 'Kishoreganj', 'Faridpur', 'Kishoreganj', 'Airport']
        ];

        foreach ($trainNames as $trainName) {
            $compartmentNumber = rand(1, 7);
            $route = $routes[array_rand($routes)]; 
            $updownNumber = count($route) - 1; 

            $trainId = DB::table('train')->insertGetId([
                'trainname' => $trainName,
                'compartmentnumber' => $compartmentNumber,
                'updownnumber' => $updownNumber,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            for ($j = 1; $j <= $compartmentNumber; $j++) {
                $compartmentType = $compartmentTypes[array_rand($compartmentTypes)]; 

                $price = round(rand(8000, 10576) / 100, 2);

                DB::table('traincompartments')->insert([
                    'trainid' => $trainId,
                    'seatnumber' => $j,
                    'compartmentname' => 'Compartment ' . $j,
                    'compartmenttype' => $compartmentType,
                    'price' => $price,  
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $baseDate = now()->addDays(rand(10, 15)); 
            $prevArrDate = $baseDate;
            $prevArrTime = now()->addHours(rand(6, 12))->format('H:i:s'); 

            for ($k = 0; $k < $updownNumber; $k++) {
                $tsource = $route[$k];
                $tdestination = $route[$k + 1];

                $tdepdate = $prevArrDate->format('Y-m-d');

                $tarrdate = clone $prevArrDate;
                if (rand(0, 1) == 1) {
                    $tarrdate = $prevArrDate->addDays(1);
                }

                if ($tarrdate->format('Y-m-d') == $tdepdate) {
                    $tarrtime = now()->setTime(rand(14, 23), rand(0, 59))->format('H:i:s');
                } else {
                    $tarrtime = now()->setTime(rand(0, 23), rand(0, 59))->format('H:i:s');
                }

                if ($k == 0) {
                    $tdeptime = $prevArrTime;
                } else {
                    $prevArrTimeObj = \Carbon\Carbon::createFromFormat('H:i:s', $prevArrTime);
                    $tdeptime = $prevArrTimeObj->addMinutes(15)->format('H:i:s');
                }

                DB::table('trainupdowns')->insert([
                    'trainid' => $trainId,
                    'tarrtime' => $tarrtime, 
                    'tdeptime' => $tdeptime,
                    'tdepdate' => $tdepdate,
                    'tarrdate' => $tarrdate->format('Y-m-d'),
                    'tsource' => $tsource . ' Railway Station',
                    'tdestination' => $tdestination . ' Railway Station',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $prevArrDate = $tarrdate;
                $prevArrTime = $tarrtime;
            }
        }
    }
}
