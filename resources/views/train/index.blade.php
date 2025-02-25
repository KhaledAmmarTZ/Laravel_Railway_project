@extends('layout.master')
@section('title')
Index Page
@endsection

@section('content')
<div class="card text-center" style="width: 100%; background-color: #f8f9fa; border: 1px solid #ccc;">
    <div class="card-header text-white" style="background-color: #005F56">
        Train List
    </div>
    <div class="card-body">
        <table class="table" border="2" id="train-table">
            <thead>
                <tr>
                    <th>Train Name</th>
                    <th>Arrival Time</th>
                    <th>Departure Time</th>
                    <th>Source</th>
                    <th>Destination</th>
                    <th>Compartment</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trains as $train)
                    @foreach ($train->trainupdowns as $updown)
                        <tr>
                            <td>{{ $train->trainname }}</td>
                            <td>{{ \Carbon\Carbon::parse($updown->tdeptime)->format('d-m-Y h:i A') }}</td>
                            <td>{{ \Carbon\Carbon::parse($updown->tarrtime)->format('d-m-Y h:i A') }}</td>
                            <td>{{ $updown->tsource }}</td>
                            <td>{{ $updown->tdestination }}</td>
                            <td>
                                <ul>
                                    @foreach ($train->traincompartments as $compartment)
                                        <li>Name: {{ $compartment->compartmentname }} (Seats: {{ $compartment->seatnumber }}) (Type: {{ $compartment->compartmenttype }})</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer text-center d-flex justify-content-center align-items-center" style="background-color: #005F56">
    <form method="GET" action="{{ route('train.index') }}" id="search-form">
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

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Listen for input on the search field
    $('#search-input').on('keyup', function() {
        var query = $(this).val();
        var searchBy = $('#search-by').val();
        
        // Perform AJAX request
        $.ajax({
            url: "{{ route('train.index') }}",
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
