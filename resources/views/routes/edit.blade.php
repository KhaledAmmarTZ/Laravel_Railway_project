@extends('layout.apps')

@section('content')
    <h2>Edit Route</h2>

    <form action="{{ route('routes.update', $route->id) }}" method="POST">
        @csrf
        @method('PUT')

    <!-- Route No Field (non-editable) -->
    <label for="route_no">Route No:</label>
    <input type="number" name="route_no" value="{{ $route->route_no }}" readonly required>

    <!-- Stations Table -->
    <table>
        <thead>
            <tr>
                <th>Source</th>
                <th>Destination</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($routes as $routeItem)
                <tr>
                    <!-- Source Dropdown -->
                    <td>
                        <select name="source[]" required>
                            @foreach ($stations as $station)
                                <option value="{{ $station->stationname }}" {{ $routeItem->source == $station->stationname ? 'selected' : '' }}>
                                    {{ $station->stationname }}
                                </option>
                            @endforeach
                        </select>
                    </td>

                    <!-- Destination Dropdown -->
                    <td>
                        <select name="destination[]" required>
                            @foreach ($stations as $station)
                                <option value="{{ $station->stationname }}" {{ $routeItem->destination == $station->stationname ? 'selected' : '' }}>
                                    {{ $station->stationname }}
                                </option>
                            @endforeach
                        </select>
                    </td>

                    <!-- Hidden input to store the route ID -->
                    <input type="hidden" name="route_id[]" value="{{ $routeItem->id }}">

                    <!-- <td>
                        <button type="button" class="edit-route-btn" data-id="{{ $routeItem->id }}" data-source="{{ $routeItem->source }}" data-destination="{{ $routeItem->destination }}">Edit</button>
                    </td> -->
                </tr>
            @endforeach
        </tbody>
    </table>
    <button type="submit">Update Route</button>
</form>
@endsection
