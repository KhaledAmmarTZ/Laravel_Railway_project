<?php

namespace App\Http\Controllers;

use App\Models\Station;  // Make sure you have the Station model
use Illuminate\Http\Request;

class StationController extends Controller
{
    /**
     * Fetch all station names.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStations()
    {
        // Fetching all stations from the database
        $stations = Station::all(['stationname']);  // Only fetch the station name

        // Returning the stations as a JSON response
        return response()->json($stations);
    }
}
