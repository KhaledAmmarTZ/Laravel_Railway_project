<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Train;
use App\Models\Station;
use App\Models\TrainRoute;

class TrainRouteController extends Controller
{
    public function index()
    {
        $trains = Train::all();
        $stations = Station::all();
        $routes = TrainRoute::orderBy('sequence')->get();

        return view('train.train_route', compact('trains', 'stations', 'routes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'trainid' => 'required|exists:train,trainid',
            'route' => 'required|array|min:2', // Must have at least two stations
            'route.*' => 'exists:stations,stid'
        ]);

        // Remove existing routes for this train
        TrainRoute::where('trainid', $request->trainid)->delete();

        foreach ($request->route as $index => $stationId) {
            TrainRoute::create([
                'trainid' => $request->trainid,
                'station_id' => $stationId,
                'sequence' => $index + 1
            ]);
        }

        return response()->json(['success' => 'Route saved successfully!']);
    }
}
