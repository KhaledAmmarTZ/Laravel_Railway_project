@extends('layout.admin')

@section('title', 'Show Train')

@section('content')
<div class="card text-center" style="width: 100%; background-color: #f8f9fa; border: 1px solid #ccc;">
    <div class="card-header text-white" style="background-color: #005F56">
      Train Details
      @if(session('success'))
        <div class="alert alert-success text-center w-100">
            {{ session('success') }}
        </div>
      @endif
    </div>
    <div class="card-body">
        <table class="table" border="2">
            <thead>
                <tr>
                    <th>Train Name</th>
                    <th>Number of Compartments</th>
                    <th>Departure Time</th>
                    <th>Arrival Time</th>
                    <th>Source</th>
                    <th>Destination</th>
                    <th>Compartments</th>
                    <th>Status</th> <!-- New Status Column -->
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trains as $train)
                    @foreach ($train->trainupdowns as $index => $updown)
                        @php
                            $currentTime = \Carbon\Carbon::now(); // Get current date and time
                            $departureTime = \Carbon\Carbon::parse($updown->tdepdate . ' ' . $updown->tdeptime); // Parse the train departure time
                            
                            $status = '';
                            
                            // Check if the departure time is in the past
                            if ($departureTime->isPast()) {
                                $status = 'Unavailable'; // Train has already departed
                            } 
                            // Check if the train is available (future departure)
                            elseif ($departureTime->isFuture()) {
                                $status = 'Available'; // Train will depart in the future
                            }
                        @endphp
                        <tr>
                            @if ($index == 0)
                                <!-- Only show train details for the first updown entry -->
                                <td rowspan="{{ count($train->trainupdowns) }}">{{ $train->trainname }}</td>
                                <td rowspan="{{ count($train->trainupdowns) }}">{{ $train->compartmentnumber }}</td>
                            @endif
                            <!-- Format the times to AM/PM -->
                            <td>
                                {{ \Carbon\Carbon::parse($updown->tarrdate)->format('d-m-Y') }} 
                                {{ \Carbon\Carbon::parse($updown->tarrtime)->format('h:i A') }}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($updown->tdepdate)->format('d-m-Y') }} 
                                {{ \Carbon\Carbon::parse($updown->tdeptime)->format('h:i A') }}
                            </td>
                            <td>{{ $updown->tsource }}</td>
                            <td>{{ $updown->tdestination }}</td>

                            @if ($index == 0)
                                <!-- Show compartments only once for the first updown row -->
                                <td rowspan="{{ count($train->trainupdowns) }}">
                                    <ul>
                                        @foreach ($train->traincompartments as $compartment)
                                            <li>Name:{{ $compartment->compartmentname }} (Seats: {{ $compartment->seatnumber }}) (Type: {{ $compartment->compartmenttype }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td rowspan="{{ count($train->trainupdowns) }}">
                                    <!-- Status Column -->
                                    <strong>{{ $status }}</strong>
                                </td>
                                <td rowspan="{{ count($train->trainupdowns) }}">
                                    <!-- Edit Button -->
                                    <a href="{{ route('train.edit', $train->trainid) }}" class="btn update-btn btn-sm">Edit</a>
                                    <!-- Delete Form -->
                                    <form action="{{ route('train.destroy', $train->trainid) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn delete-btn btn-sm" onclick="return confirm('Are you sure you want to delete this train?')">Delete</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer text-center d-flex justify-content-center align-items-center" style="background-color: #005F56">
        <form method="GET" action="{{ route('train.show') }}" id="search-form">
            <div class="mx-auto d-flex align-items-center">
                <!-- Dropdown to select search type -->
                <select name="search_by" class="form-control mx-2" aria-label="Search by" id="search-by">
                    <option value="tname" {{ request()->search_by == 'tname' ? 'selected' : '' }}>Train Name</option>
                    <option value="tsource" {{ request()->search_by == 'tsource' ? 'selected' : '' }}>Source</option>
                    <option value="tdestination" {{ request()->search_by == 'tdestination' ? 'selected' : '' }}>Destination</option>
                </select>

                <!-- Input field for search term -->
                <input class="search-bar mx-2" type="search" id="search-input" name="search" value="{{ request()->search }}" placeholder="Search" aria-label="Search">
                <button class="search-btn" type="submit">Search</button>  
            </div>
        </form>
    </div>
</div>
<script>
$(document).ready(function() {
    // Listen for input on the search field
    $('#search-input').on('keyup', function() {
        var query = $(this).val();
        var searchBy = $('#search-by').val();
        
        // Perform AJAX request
        $.ajax({
            url: "{{ route('train.show') }}", // Adjust this to your show route
            method: 'GET',
            data: {
                search: query,
                search_by: searchBy,
            },
            success: function(response) {
                // Update table with the returned HTML
                $('#train-table tbody').html(response);
            }
        });
    });
});
</script>
@endsection
