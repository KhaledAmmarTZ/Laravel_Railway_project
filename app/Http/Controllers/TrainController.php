<?php

namespace App\Http\Controllers;

use App\Models\Train;
use App\Models\Compartment;
use App\Models\Updown;
use App\Models\Station;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TrainController extends Controller
{
    // Show the create form
    public function create()
    {
        $stations = Station::all();
        return view('train.create' , compact('stations'));
    }

        // Define the convertTo24HourFormat function inside the TrainController
    private function convertTo24HourFormat($time) {
        // Convert time from 'HH:MM' to 'HH:MM:SS' 24-hour format
        return date('H:i:s', strtotime($time));
    }

    // Store the newly created train along with compartments and updown info
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'tname' => 'required|string',
        'numofcompartment' => 'required|integer|min:1',
        'compartments.*.name' => 'required|string',
        'compartments.*.seats' => 'required|integer|min:1',
        'compartments.*.type' => 'required|string',
        'updownnumber' => 'required|integer|min:1',
        'updowns.*.source' => 'required|string',
        'updowns.*.destination' => 'required|string',
        'updowns.*.deptime' => 'required|date_format:H:i',
        'updowns.*.arrtime' => 'required|date_format:H:i',
        'updowns.*.tarrdate' => 'required|date_format:Y-m-d',
        'updowns.*.tdepdate' => 'required|date_format:Y-m-d',
    ]);

    // Create Train
    $train = Train::create([
        'trainname' => $validatedData['tname'],
        'compartmentnumber' => $validatedData['numofcompartment'],
        'updownnumber' => $validatedData['updownnumber'],
    ]);

    // Create Compartments
    foreach ($validatedData['compartments'] as $compartment) {
        Compartment::create([
            'trainid' => $train->trainid,
            'compartmentname' => $compartment['name'],
            'seatnumber' => $compartment['seats'],
            'compartmenttype' => $compartment['type'],
        ]);
    }

    // Create Updown info
    foreach ($validatedData['updowns'] as $updown) {
        Updown::create([
            'trainid' => $train->trainid,
            'tsource' => $updown['source'],
            'tdestination' => $updown['destination'],
            'tdeptime' => $this->convertTo24HourFormat($updown['deptime']),  // Using the defined function
            'tarrtime' => $this->convertTo24HourFormat($updown['arrtime']),  // Using the defined function
            'tarrdate' => $updown['tarrdate'],
            'tdepdate' => $updown['tdepdate'],
        ]);
    }

    return redirect()->route('train.show')->with('success', 'Train created successfully');
}



public function index(Request $request)
    {
        $query = Updown::query();  // Paginate Updown models, not trains

        // Search filter based on user input
        if ($request->has('search') && $request->search != '') {
            $searchBy = $request->search_by ?? 'tname'; 

            if ($searchBy == 'tname') {
                // Join the train table to search by train name
                $query->whereHas('train', function($q) use ($request) {
                    $q->where('trainname', 'LIKE', '%' . $request->search . '%');
                });
            } elseif ($searchBy == 'tsource') {
                $query->where('tsource', 'LIKE', '%' . $request->search . '%');
            } elseif ($searchBy == 'tdestination') {
                $query->where('tdestination', 'LIKE', '%' . $request->search . '%');
            } elseif ($searchBy == 'tdeptime') {
                $query->where('tdeptime', 'LIKE', '%' . $request->search . '%');
            } elseif ($searchBy == 'tarrtime') {
                $query->where('tarrtime', 'LIKE', '%' . $request->search . '%');
            } elseif ($searchBy == 'tdepdate') {
                $query->where('tdepdate', 'LIKE', '%' . $request->search . '%');
            } elseif ($searchBy == 'tarrdate') {
                $query->where('tarrdate', 'LIKE', '%' . $request->search . '%');
            }
        }

        // Paginate the Updown models with a limit per page (10)
        $updowns = $query->with('train')->paginate(10); 

        // If the request is AJAX, return only the table rows (partial view)
        if ($request->ajax()) {
            return view('train.partials.train-table', compact('updowns'));
        }

        return view('train.index', compact('updowns'));
    }

    
    // This method is for showing a specific train
    public function show(Request $request)
{
    $search = $request->input('search');
    $searchBy = $request->input('search_by');

    $query = Train::with('trainupdowns');

    if ($search) {
        if ($searchBy == 'tname') {
            $query->where('trainname', 'like', "%$search%");
        } elseif ($searchBy == 'tsource') {
            $query->whereHas('trainupdowns', function ($q) use ($search) {
                $q->where('tsource', 'like', "%$search%");
            });
        } elseif ($searchBy == 'tdestination') {
            $query->whereHas('trainupdowns', function ($q) use ($search) {
                $q->where('tdestination', 'like', "%$search%");
            });
        }
    }

    $trains = $query->paginate(6); // Show 10 trains per page

    if ($request->ajax()) {
        return view('train.partials.train_table', compact('trains'))->render();
    }

    return view('train.show', compact('trains'));
}


    public function showtrain(Request $request)
    {
        // Get the current time
        $currentTime = \Carbon\Carbon::now();

        // Fetch the unavailable trains
        $unavailableTrains = Train::with('trainupdowns')
            ->whereHas('trainupdowns', function ($query) use ($currentTime) {
                $query->where('tdepdate', '<', $currentTime->format('Y-m-d'))
                    ->orWhere(function ($query) use ($currentTime) {
                        $query->where('tdepdate', '=', $currentTime->format('Y-m-d'))
                                ->where('tdeptime', '<', $currentTime->format('H:i:s'));
                    });
            })
            ->get();

        return view('admin.dashboard', compact('unavailableTrains'));
    }

    // Show the edit form
    public function edit($trainId)
    {
        $train = Train::with('traincompartments', 'trainupdowns')->findOrFail($trainId);
        return view('train.edit', compact('train'));
    }

    public function showEditPage()
    {
        // Get all trains with their associated trainupdowns
        $trains = Train::with('trainupdowns')->get();
    
        // Get the current time
        $currentTime = \Carbon\Carbon::now();
    
        // Loop through each train and check its availability
        foreach ($trains as $train) {
            // Check if train has any associated trainupdowns
            if ($train->trainupdowns->isNotEmpty()) {
                // Get the first departure date and time for the train
                $departureCarbon = \Carbon\Carbon::parse($train->trainupdowns->first()->tdepdate . ' ' . $train->trainupdowns->first()->tdeptime);
    
                // Check if the departure time has passed compared to the current time
                if ($departureCarbon->isPast()) {
                    $train->status = 'Unavailable';
                } else {
                    $train->status = 'Available';
                }
            } else {
                // If no trainupdowns are available, set as unavailable
                $train->status = 'Unavailable';
            }
        }
    
        // Pass the trains data to the view
        return view('train.list_edit', compact('trains'));
    }
    
    

    public function loadTrainData(Request $request)
    {
        $train = Train::with('traincompartments', 'trainupdowns')->findOrFail($request->trainid);
        return view('train.edit', compact('train'));
    }

    public function update(Request $request, $trainId)
    {
        $request->validate([
            'trainname' => 'required|string|max:255',
            'compartmentnumber' => 'required|integer|min:1',
            'updownnumber' => 'required|integer|min:1',
            'compartments' => 'array',
            'updowns' => 'array',
        ]);

        $train = Train::findOrFail($trainId);
        $train->trainname = $request->trainname;
        $train->compartmentnumber = $request->compartmentnumber;
        $train->updownnumber = $request->updownnumber;
        $train->save();

        // Update Compartments
        $train->traincompartments()->delete(); // Delete old compartments
        if ($request->has('compartments')) {
            foreach ($request->compartments as $compartmentData) {
                $train->traincompartments()->create([
                    'compartmentname' => $compartmentData['compartmentname'],
                    'seatnumber' => $compartmentData['seatnumber'],
                    'compartmenttype' => $compartmentData['compartmenttype'],
                ]);
            }
        }

        // Update Updowns
        $train->trainupdowns()->delete(); // Delete old updowns
        if ($request->has('updowns')) {
            foreach ($request->updowns as $updownData) {
                $train->trainupdowns()->create([
                    'tarrtime' => $updownData['tarrtime'],
                    'tdeptime' => $updownData['tdeptime'],
                    'tarrdate' => $updownData['tarrdate'],
                    'tdepdate' => $updownData['tdepdate'],
                    'tsource' => $updownData['tsource'],
                    'tdestination' => $updownData['tdestination'],
                ]);
            }
        }

        return redirect()->route('train.show')->with('success', 'Train updated successfully!');
    }

    // Delete a train
    public function destroy($trainid)
    {
        $train = Train::findOrFail($trainid);
        $train->traincompartments()->delete();
        $train->trainupdowns()->delete();
        $train->delete();

        return redirect()->route('train.show')->with('success', 'Train deleted successfully');
    }
}
