<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Passenger;
use App\Models\Compartment;
use App\Models\Station;
use App\Models\Updown;
use App\Models\Train;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use League\CommonMark\Extension\SmartPunct\EllipsesParser;

class PassengerController extends Controller
{
    public function index()
    {
        $trains = [];
        $trainIds = DB::table('train')
                    ->orderBy('created_at', 'desc') 
                    ->limit(10) 
                    ->pluck('trainid')
                    ->toArray(); 
        $i = 0;
        $j = 0;
        while($i < 5 && $j < count($trainIds))
        {
            $trainid = $trainIds[$j];
            $j += 1;
            $maxSequence = DB::table('trainupdowns')
                                ->where('trainid', $trainid)
                                ->max('sequence');
                                
            $midSequence = floor($maxSequence / 2); 
            $tsourceRecord = DB::table('trainupdowns')
            ->where('trainid', $trainid)
            ->whereIn('sequence', [1, $midSequence + 1])
            // ->where('tarrdate', Carbon::now()->format('Y-m-d'))
            ->where('tdeptime', '>', Carbon::now()->format('H:i:s'))
            ->inRandomOrder()
            ->first();

            if (!$tsourceRecord) {
                continue; 
            }

            $tsource = $tsourceRecord->tsource;
            $arrtime = $tsourceRecord->tdeptime;
            $tdepdate = $tsourceRecord->tdepdate;
            $sequence = DB::table('trainupdowns')->where('trainid', $trainid)->where('tsource', $tsource)->first()->sequence;
            if ($sequence <= $maxSequence / 2) { 
                $tdestination = DB::table('trainupdowns')
                    ->where('trainid', $trainid)
                    ->where('sequence', '=', $midSequence)
                    ->where('tarrtime', '>', Carbon::now()->format('H:i:s'))
                    ->inRandomOrder()
                    ->first();
                if (!$tdestination) {
                    continue;
                }
                $dptime = $tdestination->tarrtime;
                $tarrdate = $tdestination->tarrdate;
                $tdestination = $tdestination->tdestination;
                $dir = 'up';
                
            }  
            else
            {            
                $tdestination = DB::table('trainupdowns')
                    ->where('trainid', $trainid)
                    ->where('sequence', '=', $maxSequence)
                    ->where('tarrtime', '>', Carbon::now()->format('H:i:s'))
                    ->inRandomOrder()
                    ->first();
    
                if (!$tdestination) {
                    continue;
                }    
                $dptime = $tdestination->tarrtime;
                $tarrdate = $tdestination->tarrdate;
                $tdestination = $tdestination->tdestination; 
                $dir = 'down';
                
            }
            $tclass = DB::table('traincompartments')->where('trainid', $trainid)->get();
            $trainname = DB::table('train')->where('trainid', $trainid)->first()->trainname;
            $trains[] = [
                'trainid' => $trainid,
                'tsource' => $tsource,
                'tarrtime' => $arrtime,
                'tdestination' => $tdestination,
                'tdeptime' => $dptime,
                'tclass' => $tclass,
                'trainname' => $trainname,
                'tarrdate' => $tarrdate,
                'tdepdate' => $tdepdate,
                'direction' => $dir,
            ];
            $i += 1;
        }
        return view('passenger.index', compact('trains'));
    }

    public function availableCreate(Request $request)
    {
        $tra = [
            'tsource' => $request->tsource,
            'tdestination' => $request->tdestination,
            'arrdate' => $request->arrdate,
            'tclass' => $request->tclass,
            'trainid' => $request->trainid,
            'price' => $request->price,
            'available_seats' => $request->available_seats,
            'direction' => $request->direction,
            'tarrtime' => $request->tarrtime,
            'tdeptime' => $request->tdeptime,
            'trainname' => $request->trainname,
        ];
        session(['tra' => $tra]);
        $train = $tra;
        return view('passenger.available_create', compact('train'));
    }

    public function availableStore(Request $request)
    {
        $tra = session('tra');
        $request->validate([
            'mealop' => 'nullable',
            'ticket_count' => 'required|integer|min:1|max:4',
        ]);
        $seat_remain = $tra['available_seats'] - $request->ticket_count;

        if($seat_remain <= 0){
            return redirect()->route('passenger.avilableCreate')->with('msg', 'Enough seats are not available');
        }
        $passengerData = [];
        for ($i = 0; $i < $request->ticket_count; $i++) {
            $passengerData[] = [
                'mealop' => $request->mealop == true ? 1 : 0,
                'user_id' => Auth::id(),
                'trainid' => $tra['trainid'],
                'tsource' => $tra['tsource'],
                'tdest' => $tra['tdestination'],
                'arrtime' => $tra['tdeptime'],
                'dptime' => $tra['tarrtime'],
                'arrdate' => $tra['arrdate'],
                'tclass' => $tra['tclass'],
                'pstatus' => 'waiting',
                'price' => $tra['price'],
            ];
        }
        // dd($passengerData);
        DB::table('passengers')->insert($passengerData);
        $user_id = collect($passengerData)->pluck('user_id')->first();
        return redirect()->route('payment.index', ['pnr' => $user_id])->with('msg', 'Please complete your payment within 15 minutes, otherwise your seat(s) will be cancelled.');
    }

    public function create()
    {
        $ticket_count = DB::table('passengers')
            ->where('user_id', Auth::id())
            ->where('pstatus', 'waiting')
            ->count();
        if ($ticket_count > 0) {
            DB::table('passengers')
                ->where('user_id', Auth::id())
                ->where('pstatus', 'waiting')
                ->delete();
        }
        $tra = [
            'tsource' => session('tsource'),
            'tdestination' => session('tdestination'),
            'arrdate' => session('arrdate'),
            'tclass' => session('tclass'),
            'trainid' => session('trainid'),
            'price' => session('price'),
            'seatnumber' => session('seatnumber'),
        ];
        $trains = collect(session('trains'));
        if (empty($tra['trainid'])) {
            return redirect()->route('passenger.search')->with('error', 'No train data available');
        }
        $train = $trains->where('trainid', $tra['trainid'])
                        ->where('tsource', $tra['tsource'])
                        ->where('compartmenttype', $tra['tclass'])
                        ->where('tarrdate', $tra['arrdate'])
                        ->first();
        session(['train' => $train]);
        // dd($train);
        return view('passenger.create', compact('train'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Fetch the selected train and compartment details from session
        $tra = [
            'tsource' => session('tsource'),
            'tdestination' => session('tdestination'),
            'arrdate' => session('arrdate'),
            'tclass' => session('tclass'),
            'trainid' => session('trainid'),
            'price' => session('price'),
            'available_seats' => session('available_seats'),
            'direction' => session('direction'),
        ];
    
        session(['tra' => $tra]);
        
        $train = session('train');
        
        // Check if the train exists in the database
        $traindep = DB::table('trainupdowns')
            ->join('traincompartments', 'trainupdowns.trainid', '=', 'traincompartments.trainid')
            ->where('trainupdowns.trainid', $tra['trainid'])
            ->where('trainupdowns.tdestination', $tra['tdestination'])
            ->where('traincompartments.compartmenttype', $tra['tclass'])
            ->select('trainupdowns.*', 'traincompartments.*')
            ->first();
    
        $traindep1 = DB::table('trainupdowns')
            ->join('traincompartments', 'trainupdowns.trainid', '=', 'traincompartments.trainid')
            ->where('trainupdowns.trainid', $tra['trainid'])
            ->where('trainupdowns.tsource', $tra['tsource'])
            ->where('traincompartments.compartmenttype', $tra['tclass'])
            ->select('trainupdowns.*', 'traincompartments.*')
            ->first();
        
        // If the train is not found, return an error
        if (!$train) {
            return back()->withErrors(['trainid' => 'Selected train not found']);
        }
        
        $tdeptime = $traindep1->tdeptime; // Departure time from source station
        $tarrtime = $traindep->tarrtime; // Arrival time at destination station
    
        // Validate the request data
        $request->validate([
            'mealop' => 'nullable',
            'ticket_count' => 'required|integer|min:1|max:4',
        ]);
    
        // Check if there are enough available seats
        $seat_remain = $tra['available_seats'] - $request->ticket_count;
        
        if ($seat_remain <= 0) {
            return redirect()->route('passenger.create')->with('msg', 'Enough seats are not available');
        }
    
        // Prepare the passenger data to be inserted into the database
        $passengerData = [];
        for ($i = 0; $i < $request->ticket_count; $i++) {
            $passengerData[] = [
                'mealop' => $request->mealop == 'on' ? 1 : 0, // Convert to 1 or 0 based on the checkbox
                'user_id' => Auth::id(),
                'trainid' => $tra['trainid'],
                'tsource' => $tra['tsource'],
                'tdest' => $tra['tdestination'],
                'arrtime' => $tdeptime,
                'dptime' => $tarrtime,
                'arrdate' => $tra['arrdate'],
                'tclass' => $tra['tclass'],
                'pstatus' => 'waiting', // Set initial status as 'waiting'
                'price' => $tra['price'],
            ];
        }
    
        // Insert the passenger data into the passengers table
        DB::table('passengers')->insert($passengerData);
    
        // Optional: Handle seat count update if required
        // You can use this logic to update available seats based on the direction (up/down)
        // if ($tra['direction'] == 'up') {
        //     DB::table('traincompartments')
        //         ->where('trainid', $tra['trainid'])
        //         ->where('compartmenttype', $tra['tclass'])
        //         ->update(['available_seats_up' => $seat_remain]);
        // } else {
        //     DB::table('traincompartments')
        //         ->where('trainid', $tra['trainid'])
        //         ->where('compartmenttype', $tra['tclass'])
        //         ->update(['available_seats_down' => $seat_remain]);
        // }
    
        // Fetch the user ID from the inserted passenger data
        $user_id = collect($passengerData)->pluck('user_id')->first();
    
        // Redirect to the payment page with a message
        return redirect()->route('payment.index', ['pnr' => $user_id])
            ->with('msg', 'Please complete your payment within 15 minutes, otherwise your seat(s) will be cancelled.');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Passenger $passenger)
    {
        return view('passenger.show')->with('passenger', $passenger);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Passenger $passenger)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Passenger $passenger)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Passenger $passenger)
    {
        DB::table('passengers')->where('pnr', $passenger->pnr)->update(['pstatus' => 'canceled']);
        return redirect()->route('passenger.index')->with('error', 'Ticket deleted successfully');
    }

    public function search(){
        return view('passenger.search');
    }
    
    public function searchForm(Request $request)
    {
        $date = Carbon::parse($request->arrdate)->toDateString();
        $currentTime = Carbon::now()->format('H:i:s');

        $trains = DB::table('trainupdowns')
                    ->join('train', 'trainupdowns.trainid', '=', 'train.trainid') 
                    ->join('traincompartments', 'trainupdowns.trainid', '=', 'traincompartments.trainid')
                    ->where('trainupdowns.tsource', $request->tsource)
                    ->where('trainupdowns.tarrdate', $date) 
                    // ->where('trainupdowns.tdeptime', '>', $currentTime)
                    ->select('train.*', 'trainupdowns.*', 'traincompartments.*')
                    ->get();

        $matchingTrains = [];

        foreach ($trains as $train) {
            $trainid = $train->trainid;
            $tsource = $train->tsource;

            $sequence = DB::table('trainupdowns')
                        ->where('trainid', $trainid)
                        ->where('tsource', $tsource)
                        ->first()->sequence;

            $maxSequence = DB::table('trainupdowns')->where('trainid', $trainid)->max('sequence');

            if ($sequence <= $maxSequence / 2) {
                $tdestination = DB::table('trainupdowns')
                                    ->where('trainid', $trainid)
                                    ->where('sequence', '>=', $sequence)
                                    ->where('sequence', '<=', $maxSequence / 2)
                                    ->pluck('tdestination');
            } else {
                $tdestination = DB::table('trainupdowns')
                                    ->where('trainid', $trainid)
                                    ->where('sequence', '>=', $sequence)
                                    ->where('sequence', '<=', $maxSequence)
                                    ->pluck('tdestination');
            }

            if (in_array($request->tdestination, $tdestination->toArray())) {
                $matchingTrains[] = $train;
            }
        }

        if (!empty($matchingTrains)) {
            session([
                'tsource' => $request->tsource,
                'tdestination' => $request->tdestination,
                'arrdate' => $date,
                'trains' => $matchingTrains 
            ]);
            return redirect()->route('passenger.traininfo');

        } else {
            
            $sources = DB::table('trainupdowns')->select('tsource')->distinct()->get();
            $destinations = DB::table('trainupdowns')->select('tdestination')->distinct()->get();

            return redirect()->route('passenger.search')
                            ->with([
                                'error' => 'No trains available for the selected route!',
                                'sources' => $sources,
                                'destinations' => $destinations
                            ]);
        }
    }

    public function autocomplete(Request $request)
    {
        $data = [];

        if ($request->filled('q') && $request->filled('type')) {
            $query = Station::query();

            if ($request->get('type') === 'tsource') {
                $data = $query->select("stid as id", "stationname as name")
                            ->where('stationname', 'LIKE', '%' . $request->get('q') . '%')
                            ->take(10)
                            ->get();
            } elseif ($request->get('type') === 'tdestination') {
                $data = $query->select("stid as id", "stationname as name")
                            ->where('stationname', 'LIKE', '%' . $request->get('q') . '%')
                            ->take(10)
                            ->get();
            }
        }
        return response()->json($data);
    }

    public function traininfo()
    {
        $trains = collect(session('trains'))->unique('trainid')->values();
        
        // Process ALL compartments (not unique) for the trainMap
        $trainMap = collect(session('trains'))
            ->groupBy('trainid')
            ->map(function ($trainGroup) {
                return $trainGroup->map(function ($compartment) {
                    $trainid = $compartment->trainid;
                    $tsource = $compartment->tsource;

                    $sequence = DB::table('trainupdowns')
                        ->where('trainid', $trainid)
                        ->where('tsource', $tsource)
                        ->first()->sequence;

                    $maxSequence = DB::table('trainupdowns')->where('trainid', $trainid)->max('sequence');

                    $trainDirection = $sequence <= $maxSequence / 2 ? 'up' : 'down';

                    return [
                        'compartment_name' => $compartment->compartmenttype,
                        'price' => $compartment->price,
                        'total_seats' => $compartment->total_seats,
                        'available_seats' => $compartment->{'available_seats_' . ($trainDirection === 'up' ? 'up' : 'down')},
                        'direction' => $trainDirection,
                    ];
                });
            });

        // dd($trainMap);
        return view('passenger.traininfo', compact(['trains', 'trainMap']));
    }

    public function traininfosubmission(Request $request)
    {
        session([
            'trainid' => $request->trainid,
            'trainname' => $request->trainname,
            'tclass' => $request->compartment,
            'price' => $request->price,
            'available_seats' => $request->available_seats,
            'direction' => $request->direction,
        ]);
        return redirect()->route('passenger.create');
    }

    public function getInfo($id)
    {
        $trains = session('trains'); 
        $trains = collect($trains)->unique('trainid')->values();
        $train = $trains->firstWhere('trainid', $id);
        if (!$train) {
            return response()->json(['error' => 'Train not found'], 404);
        }
        return response()->json($train);
    }

    public function getInfoTrain($id)
    {
        $train = Train::where('trainid', $id)->first();
        if (!$train) {
            return response()->json(['error' => 'Train not found'], 404);
        };
        return response()->json($train);
    }

}