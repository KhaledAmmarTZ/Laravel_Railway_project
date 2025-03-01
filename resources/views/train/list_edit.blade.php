@extends('layout.admin')

@section('title', 'Select Train to Edit')

@section('content')  
    <div class="card text-center" style="width: 100%; background-color: #f8f9fa; border: 1px solid #ccc;">
        <div class="card-header text-white" style="background-color: #005F56">
            <h2>Select a Train to Edit</h2>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Train Name</th>
                        <th>Train Description</th>
                        <th>Availability</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trains as $train)
                        @php
                            $arrivalDate = $arrivalTime = $departureDate = $departureTime = null;
                            $availability = 'Unavailable';  // Default is Unavailable

                            // Check if trainupdowns is not empty
                            if ($train->trainupdowns->isNotEmpty()) {
                                $arrivalDate = \Carbon\Carbon::parse($train->trainupdowns->first()->tarrdate)->format('d-m-Y');
                                $arrivalTime = \Carbon\Carbon::parse($train->trainupdowns->first()->tarrtime)->format('h:i A');
                                $departureDate = \Carbon\Carbon::parse($train->trainupdowns->first()->tdepdate)->format('d-m-Y');
                                $departureTime = \Carbon\Carbon::parse($train->trainupdowns->first()->tdeptime)->format('h:i A');

                                // Check if the current date is before the departure date
                                $currentDate = \Carbon\Carbon::now();
                                $departureDatetime = \Carbon\Carbon::parse($train->trainupdowns->first()->tdepdate . ' ' . $train->trainupdowns->first()->tdeptime);

                                if ($currentDate->lessThanOrEqualTo($departureDatetime)) {
                                    $availability = 'Available';  // Train is available if current date is before departure
                                }
                            }
                        @endphp
                        <tr>
                            <td>{{ $train->trainname }}</td>
                            <td>
                                @if($arrivalDate && $arrivalTime && $departureDate && $departureTime)
                                    Arrival: ({{ $arrivalDate }} {{ $arrivalTime }})<br>
                                    Departure: ({{ $departureDate }} {{ $departureTime }})
                                @else
                                    Data not available
                                @endif
                            </td>
                            <td>{{ $availability }}</td>  <!-- Show availability -->
                            <td><a href="{{ route('train.edit', $train->trainid) }}" class="btn btn-primary">Edit</a>/td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
