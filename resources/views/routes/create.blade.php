@extends('layout.apps')

@section('content')
    <h2>Create Route</h2>

    <form action="{{ route('routes.store') }}" method="POST">
        @csrf

        <!-- Route No (dynamically set) -->
        <label for="route_no">Route No:</label>
        <input type="number" name="route_no" value="{{ $nextRouteNo }}" readonly required>

        <!-- Stations Table -->
        <table id="stations-table">
            <thead>
                <tr>
                    <th>Source</th>
                    <th>Destination</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr class="station-row">
                    <td>
                        <select name="source[]" required>
                            <option value="">Select Source</option>
                            @foreach ($stations as $station)
                                <option value="{{ $station->stationname }}">{{ $station->stationname }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="destination[]" required>
                            <option value="">Select Destination</option>
                            @foreach ($stations as $station)
                                <option value="{{ $station->stationname }}">{{ $station->stationname }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <button type="button" class="add-row-btn">Add Row</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <button type="submit">Create Route</button>
    </form>

    <script>
        document.querySelectorAll('.add-row-btn').forEach(button => {
            button.addEventListener('click', function() {
                // Create a new row with source and destination options
                const newRow = document.querySelector('.station-row').cloneNode(true);
                newRow.querySelectorAll('select').forEach(select => {
                    select.selectedIndex = 0; // Reset the selection to the default empty value
                });
                document.querySelector('#stations-table tbody').appendChild(newRow);
            });
        });
    </script>
@endsection
