<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    /**
     * Display a listing of stations.
     */
    public function index()
    {
        $stations = Station::all();
        return view('station.index', compact('stations'));
    }

    /**
     * Show the form for creating a new station.
     */
    public function create()
    {
        return view('station.create');
    }

    /**
     * Store a newly created station in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'stationname' => 'required|string|max:255',
            'city' => 'required|string|max:255',
        ]);

        Station::create($validated);
        return redirect()->route('station.index')->with('success', 'Station added successfully!');
    }

    /**
     * Display the specified station.
     */
    public function show(Station $station)
    {
        return view('station.show', compact('station'));
    }

    /**
     * Show the form for editing the specified station.
     */
    public function edit(Station $station)
    {
        return view('station.edit', compact('station'));
    }

    /**
     * Update the specified station in the database.
     */
    public function update(Request $request, Station $station)
{
    $request->validate([
        'stationname' => 'required|string|max:255',
        'city' => 'required|string|max:255',
    ]);

    $station->update($request->all());

    return redirect()->route('station.index')->with('success', 'Station updated successfully!');
}


    /**
     * Remove the specified station from the database.
     */
    public function destroy(Station $station)
    {
        $station->delete();
        return redirect()->route('station.index')->with('success', 'Station deleted successfully!');
    }
}