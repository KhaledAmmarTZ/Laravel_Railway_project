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

                $seats = rand(30, 40); 
            
                $price = round(rand(8000, 10576) / 100, 2);
            
                DB::table('traincompartments')->insert([
                    'trainid' => $trainId,
                    'total_seats' => $seats, 
                    'available_seats' => $seats,
                    'booked_seats' => 0,
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
                $tdestination = $route[$k + 1] ?? null;
            
                $tdepdate = $prevArrDate->format('Y-m-d');
            
                if ($k == 0) {
                    $tdeptime = \Carbon\Carbon::createFromFormat('H:i:s', $prevArrTime)
                        ->format('H:i:00'); 
                } else {
                    $prevArrTimeObj = \Carbon\Carbon::createFromFormat('H:i:s', $prevArrTime);
                    $tdeptime = $prevArrTimeObj->addMinutes(15)->format('H:i:00'); 
                }
            
                $tdeptimeObj = \Carbon\Carbon::createFromFormat('H:i:s', $tdeptime);
            
                $hoursToAdd = rand(6, 8);
                $tarrtimeObj = $tdeptimeObj->copy()->addHours($hoursToAdd);
                $tarrtime = $tarrtimeObj->format('H:i:00'); 
            
                if ($tdeptimeObj->hour >= 12 && $tarrtimeObj->hour < 12) {
                    $tarrdate = $prevArrDate->copy()->addDay()->format('Y-m-d');
                } else {
                    $tarrdate = $tdepdate;
                }
            
                DB::table('trainupdowns')->insert([
                    'trainid' => $trainId,
                    'tarrtime' => $tarrtime,
                    'tdeptime' => $tdeptime,
                    'tdepdate' => $tdepdate,
                    'tarrdate' => $tarrdate,
                    'tsource' => $tsource . ' Railway Station',
                    'tdestination' => $tdestination ? $tdestination . ' Railway Station' : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            
                $prevArrDate = \Carbon\Carbon::createFromFormat('Y-m-d', $tarrdate);
                $prevArrTime = $tarrtime;
            }            
        }
    }
}
