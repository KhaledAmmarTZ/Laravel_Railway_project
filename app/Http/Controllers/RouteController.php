<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Station;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        $routes = Route::all();
    
        return view('routes.index', compact('routes'));
    }
    

public function create()
{
    // Fetch the next route_no (one greater than the highest route_no)
    $lastRoute = Route::orderBy('route_no', 'desc')->first();
    $nextRouteNo = $lastRoute ? $lastRoute->route_no + 1 : 1; // If no routes exist, start with 1

    // Fetch all stations from the stations table
    $stations = Station::all();

    return view('routes.create', compact('nextRouteNo', 'stations'));
}

public function store(Request $request)
{
    // Validation
    $request->validate([
        'route_no' => 'required|integer',
        'source' => 'required|array',
        'destination' => 'required|array',
        'source.*' => 'required|string',
        'destination.*' => 'required|string',
    ]);

    // Loop through source-destination pairs and create routes
    foreach ($request->source as $index => $source) {
        $route = new Route();
        $route->route_no = $request->route_no;
        $route->source = $source;
        $route->destination = $request->destination[$index];
        $route->save();
    }

    return redirect()->route('routes.index')->with('success', 'Route created successfully!');
}


    public function edit($id)
    {
        $route = Route::findOrFail($id);
        $routes = Route::where('route_no', $route->route_no)->get();
        $stations = Station::all(); // Fetch all stations from the database
    
        return view('routes.edit', compact('route', 'routes', 'stations'));
    }
    
      
    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'route_no' => 'required|integer',
            'source' => 'required|array', // Ensure it's an array
            'destination' => 'required|array', // Ensure it's an array
        ]);
    
        // Find the route by ID
        $route = Route::findOrFail($id);
        $route->route_no = $request->route_no;
        $route->save();
    
        // Loop through sources and destinations to update each one
        foreach ($request->source as $index => $source) {
            // Get the routeItem by its ID
            $routeItem = Route::findOrFail($request->route_id[$index]); // Assuming you send route IDs along with the data
            $routeItem->source = $source;
            $routeItem->destination = $request->destination[$index];
            $routeItem->save();
        }
    
        // Redirect with success message
        return redirect()->route('routes.index')->with('success', 'Route updated successfully!');
    }
     

    public function destroy(Route $route)
    {
        Route::where('route_no', $route->route_no)->delete();
        return redirect()->route('routes.index')->with('success', 'Route deleted successfully.');
    }
    
}
