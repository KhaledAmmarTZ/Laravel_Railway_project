<?php

namespace App\Http\Controllers;

use App\Models\Train;
use App\Models\Compartment;
use App\Models\Updown;
use Illuminate\Http\Request;

class TrainController extends Controller
{
    // Show the create form
    public function create()
    {
        return view('train.create');
    }

    // Store the newly created train along with compartments and updown info
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tname' => 'required|string',
            'numofcompartment' => 'required|integer|min:1',
            'compartments.*.name' => 'required|string',
            'compartments.*.seats' => 'required|integer|min:1',
            'updownnumber' => 'required|integer|min:1',
            'updowns.*.source' => 'required|string',
            'updowns.*.destination' => 'required|string',
            'updowns.*.deptime' => 'required|date_format:H:i',
            'updowns.*.arrtime' => 'required|date_format:H:i',
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
            ]);
        }

        // Create Updown info
        foreach ($validatedData['updowns'] as $updown) {
            Updown::create([
                'trainid' => $train->trainid,
                'tsource' => $updown['source'],
                'tdestination' => $updown['destination'],
                'tdeptime' => $updown['deptime'],
                'tarrtime' => $updown['arrtime'],
            ]);
        }

        return redirect()->route('train.show')->with('success', 'Train created successfully');
    }

    public function index(Request $request)
    {
        $query = Train::query();
    
        // Search filter based on user input
        if ($request->has('search') && $request->search != '') {
            $searchBy = $request->search_by ?? 'tname'; // Default search by train name
    
            if ($searchBy == 'tname') {
                $query->where('trainname', 'LIKE', '%' . $request->search . '%');
            } elseif ($searchBy == 'tsource') {
                $query->whereHas('trainupdowns', function($q) use ($request) {
                    $q->where('tsource', 'LIKE', '%' . $request->search . '%');
                });
            } elseif ($searchBy == 'tdestination') {
                $query->whereHas('trainupdowns', function($q) use ($request) {
                    $q->where('tdestination', 'LIKE', '%' . $request->search . '%');
                });
            }
        }
    
        $trains = $query->with('trainupdowns', 'traincompartments')->get();
    
        // If the request is AJAX, return only the table rows
        if ($request->ajax()) {
            return view('train.partials.train-table', compact('trains'));
        }
    
        return view('train.index', compact('trains'));
    }
    

    

    // This method is for showing a specific train
    public function show(Request $request)
{
    $query = Train::query();
    
    // Search filter based on user input
    if ($request->has('search') && $request->search != '') {
        $searchBy = $request->search_by ?? 'tname'; // Default search by train name

        // Apply filter based on selected search type
        if ($searchBy == 'tname') {
            $query->where('trainname', 'LIKE', '%' . $request->search . '%');
        } elseif ($searchBy == 'tsource') {
            $query->whereHas('trainupdowns', function($q) use ($request) {
                $q->where('tsource', 'LIKE', '%' . $request->search . '%');
            });
        } elseif ($searchBy == 'tdestination') {
            $query->whereHas('trainupdowns', function($q) use ($request) {
                $q->where('tdestination', 'LIKE', '%' . $request->search . '%');
            });
        }
    }

    // Get the filtered trains with related data
    $trains = $query->with('trainupdowns', 'traincompartments')->get();
    
    // If the request is AJAX, return only the table rows
    if ($request->ajax()) {
        return view('train.partials.train-table', compact('trains'));
    }

    // Return the main view
    return view('train.show', compact('trains'));
}

    // Show the edit form
    public function edit($trainId)
    {
        $train = Train::with('traincompartments', 'trainupdowns')->findOrFail($trainId);
        return view('train.edit', compact('train'));
    }


    public function showEditPage()
    {
        $trains = Train::all();
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
