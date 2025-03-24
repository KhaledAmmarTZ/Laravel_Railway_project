<?php

namespace App\Http\Controllers;

use App\Models\Train;
use App\Models\Compartment;
use App\Models\Updown;
use App\Models\Station;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TrainController extends Controller
{
    public function create()
    {
        $stations = Station::all();
        return view('train.create', compact('stations'));
    }
    
    private function convertTo24HourFormat($time) {
        return date('H:i:s', strtotime($time));
    }

    public function store(Request $request)
    {
        // Validation for incoming request
        $validatedData = $request->validate([
            'tname' => 'required|string',
            'numofcompartment' => 'required|integer|min:1',
            'compartments.*.name' => 'required|string',
            'compartments.*.seats' => 'required|integer|min:1',
            'compartments.*.type' => 'nullable|string', // compartment type is optional
            'compartments.*.price' => 'nullable|numeric|min:0', // Validate price field (optional and numeric)
            'updownnumber' => 'required|integer|min:1',
            'updowns.*.source' => 'required|string',
            'updowns.*.destination' => 'required|string',
            'updowns.*.deptime' => 'required|date_format:H:i',
            'updowns.*.arrtime' => 'required|date_format:H:i',
            'updowns.*.tarrdate' => 'required|date_format:Y-m-d',
            'updowns.*.tdepdate' => 'required|date_format:Y-m-d',
            'train_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate the image
        ]);
    
        // Handle train image upload
        $trainImage = null;
        if ($request->hasFile('train_image')) {
            $trainImage = $request->file('train_image')->store('train_images', 'public');
        }
    
        // Create the train record
        $train = Train::create([
            'trainname' => $validatedData['tname'],
            'compartmentnumber' => $validatedData['numofcompartment'],
            'updownnumber' => $validatedData['updownnumber'],
            'train_image' => $trainImage,  // Save the image path in the database
        ]);
    
        // Create compartments for the train
        foreach ($validatedData['compartments'] as $compartment) {
            Compartment::create([  // Fixed: TrainCompartment model
                'trainid' => $train->trainid,  // Fixed: Using `id` instead of `trainid`
                'compartmentname' => $compartment['name'],
                'seatnumber' => $compartment['seats'],
                'compartmenttype' => $compartment['type'],  // Correct column
                'price' => $compartment['price'] ?? null,  // Price field (optional)
            ]);
        }
    
        // Create up-down route records for the train
        foreach ($validatedData['updowns'] as $updown) {
            Updown::create([
                'trainid' => $train->trainid,  // Fixed: Using `id` instead of `trainid`
                'tsource' => $updown['source'],
                'tdestination' => $updown['destination'],
                'tdeptime' => $this->convertTo24HourFormat($updown['deptime']),
                'tarrtime' => $this->convertTo24HourFormat($updown['arrtime']),
                'tarrdate' => $updown['tarrdate'],
                'tdepdate' => $updown['tdepdate'],
            ]);
        }
    
        // Redirect to the train show page with success message
        return redirect()->route('train.show')->with('success', 'Train created successfully');
    }
    

    public function index(Request $request)
    {
        $query = Updown::query();  

        if ($request->has('search') && $request->search != '') {
            $searchBy = $request->search_by ?? 'tname'; 

            if ($searchBy == 'tname') {
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

        $updowns = $query->with('train')->paginate(10); 


        if ($request->ajax()) {
            return view('train.partials.train-table', compact('updowns'));
        }

        return view('train.index', compact('updowns'));
    }

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

        $trains = $query->paginate(6); 

        if ($request->ajax()) {
            return view('train.partials.train_table', compact('trains'))->render();
        }

        return view('train.show', compact('trains'));
    }


    public function showtrain(Request $request)
    {
        $currentTime = \Carbon\Carbon::now();

        $unavailableTrains = Train::with('trainupdowns')
            ->whereHas('trainupdowns', function ($query) use ($currentTime) {
                $query->where('tdepdate', '<', $currentTime->format('Y-m-d'))
                    ->orWhere(function ($query) use ($currentTime) {
                        $query->where('tdepdate', '=', $currentTime->format('Y-m-d'))
                            ->where('tdeptime', '<', $currentTime->format('H:i:s'));
                    });
            })
            ->get();

        $totalTrains = Train::count();

        $availableTrains = $totalTrains - $unavailableTrains->count();

        return view('admin.dashboard', compact('unavailableTrains', 'availableTrains'));
    }

    public function edit($id)
    {
        $train = Train::with('traincompartments', 'trainupdowns')->findOrFail($id);
        $stations = Station::all(); 

        return view('train.edit', compact('train', 'stations'));
    }


    public function showEditPage()
    {
        $trains = Train::with('trainupdowns')->get();
    
        $currentTime = \Carbon\Carbon::now();
    
        foreach ($trains as $train) {
            if ($train->trainupdowns->isNotEmpty()) {
                $departureCarbon = \Carbon\Carbon::parse($train->trainupdowns->first()->tdepdate . ' ' . $train->trainupdowns->first()->tdeptime);
    
                if ($departureCarbon->isPast()) {
                    $train->status = 'Unavailable';
                } else {
                    $train->status = 'Available';
                }
            } else {
                $train->status = 'Unavailable';
            }
        }

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
            'train_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $train = Train::findOrFail($trainId);
        // Handle image upload if a new file is provided
    if ($request->hasFile('train_image')) {
        // Delete the old image from storage if it exists
        if ($train->train_image && Storage::exists('public/' . $train->train_image)) {
            Storage::delete('public/' . $train->train_image);
        }

        // Store the new image and get the path
        $trainImage = $request->file('train_image')->store('train_images', 'public');
        $train->train_image = $trainImage;
    }
        $train->trainname = $request->trainname;
        $train->compartmentnumber = $request->compartmentnumber;
        $train->updownnumber = $request->updownnumber;
        $train->save();

        $train->traincompartments()->delete(); 
        if ($request->has('compartments')) {
            foreach ($request->compartments as $compartmentData) {
                $train->traincompartments()->create([
                    'compartmentname' => $compartmentData['compartmentname'],
                    'seatnumber' => $compartmentData['seatnumber'],
                    'compartmenttype' => $compartmentData['compartmenttype'],
                    'price' => $compartmentData['price'],
                ]);
            }
        }

        $train->trainupdowns()->delete();
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

    public function destroy($trainid)
    {
        $train = Train::findOrFail($trainid);
        $train->traincompartments()->delete();
        $train->trainupdowns()->delete();
        $train->delete();

        return redirect()->route('train.show')->with('success', 'Train deleted successfully');
    }

    public function getStations()
    {
        $stations = Station::all(['stationname']);  

        return response()->json($stations);
    }

    public function getTrains()
    {
        $trains = Train::all(); 
        return response()->json($trains);
    }
}