<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Train;
use App\Models\TNameOfCompartment;
use App\Models\TDepTime;
use App\Models\TArrTime;
use App\Models\TSource;
use App\Models\TDesti;

class TrainController extends Controller
{
    // Display the form to create a new train
    public function create()
    {
        return view('train.create');
    }

    // Store the new train data in the database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tname' => 'required|string',
            'numofcompartment' => 'required|integer',
            'deptime' => 'required|date_format:H:i',
            'arrtime' => 'required|date_format:H:i',
            'source' => 'required|string',
            'destination' => 'required|string',
            'compartments' => 'required|array',
            'compartments.*.name' => 'required|string',
            'compartments.*.seats' => 'required|integer',
        ]);

        // Create the train
        $train = Train::create([
            'tname' => $validated['tname'],
            'numofcompartment' => $validated['numofcompartment']
        ]);

        // Save compartments
        foreach ($validated['compartments'] as $compartment) {
            TNameOfCompartment::create([
                'tid' => $train->tid,
                'nameofeachcompartment' => $compartment['name'],
                'numofseat' => $compartment['seats'],
            ]);
        }

        // Save departure time
        TDepTime::create([
            'tid' => $train->tid,
            'deptime' => $validated['deptime'],
        ]);

        // Save arrival time
        TArrTime::create([
            'tid' => $train->tid,
            'arrtime' => $validated['arrtime'],
        ]);

        // Save source
        TSource::create([
            'tid' => $train->tid,
            'source' => $validated['source'],
        ]);

        // Save destination
        TDesti::create([
            'tid' => $train->tid,
            'destination' => $validated['destination'],
        ]);

        return redirect()->back()->with('success', 'Train added successfully!');
    }

    // List all trains with their details
    public function index()
    {
        $trains = Train::with(['compartments', 'deptime', 'arrtime', 'source', 'destination'])->get();
        return view('train.index', compact('trains'));
    }

    // Display the edit form for a specific train
    public function edit($id)
    {
        $train = Train::with(['compartments', 'deptime', 'arrtime', 'source', 'destination'])->findOrFail($id);
        return view('train.edit', compact('train'));
    }

    // Display the page with a list of all trains for editing
    public function showEditPage()
    {
        $trains = Train::all();
        return view('train.list_edit', compact('trains'));
    }

    // Load the selected train's data into the edit form
    public function loadTrainData(Request $request)
    {
        $train = Train::with(['compartments', 'deptime', 'arrtime', 'source', 'destination'])->find($request->train_id);

        if (!$train) {
            return back()->with('error', 'Train not found!');
        }

        return view('train.edit', compact('train'));
    }

    // Update the train details
    public function update(Request $request, $id)
{
    // Find the train
    $train = Train::findOrFail($id);

    // Validate only the fields that are required
    $validated = $request->validate([
        'tname' => 'nullable|string',
        'numofcompartment' => 'nullable|integer',
        'deptime' => 'nullable', // Removed date format validation
        'arrtime' => 'nullable', // Removed date format validation
        'source' => 'nullable|string',
        'destination' => 'nullable|string',
        'compartments' => 'nullable|array',
        'compartments.*.name' => 'nullable|string',
        'compartments.*.seats' => 'nullable|integer',
    ]);

    // Update only the fields that are present in the request
    if (isset($validated['tname'])) {
        $train->tname = $validated['tname'];
    }

    if (isset($validated['numofcompartment'])) {
        $train->numofcompartment = $validated['numofcompartment'];
    }

    // Only update time fields if they are present in the request
    if ($request->has('deptime') && !empty($validated['deptime'])) {
        $train->deptime()->update(['deptime' => $validated['deptime']]);
    }

    if ($request->has('arrtime') && !empty($validated['arrtime'])) {
        $train->arrtime()->update(['arrtime' => $validated['arrtime']]);
    }

    if (isset($validated['source'])) {
        $train->source()->update(['source' => $validated['source']]);
    }

    if (isset($validated['destination'])) {
        $train->destination()->update(['destination' => $validated['destination']]);
    }

    // Update compartments if they are present
    if (isset($validated['compartments'])) {
        // Delete old compartments
        $train->compartments()->delete();

        // Create new compartments
        foreach ($validated['compartments'] as $compartment) {
            TNameOfCompartment::create([
                'tid' => $train->tid,
                'nameofeachcompartment' => $compartment['name'],
                'numofseat' => $compartment['seats'],
            ]);
        }
    }

    // Save the train
    $train->save();

    return redirect()->route('train.edit.page')->with('success', 'Train updated successfully!');
}


    // Delete the train from the database
    public function destroy($id)
    {
        $train = Train::findOrFail($id);

        // Delete related records
        $train->compartments()->delete();
        $train->deptime()->delete();
        $train->arrtime()->delete();
        $train->source()->delete();
        $train->destination()->delete();

        // Now delete the train itself
        $train->delete();

        return redirect()->route('train.index')->with('success', 'Train deleted successfully!');
    }
}
