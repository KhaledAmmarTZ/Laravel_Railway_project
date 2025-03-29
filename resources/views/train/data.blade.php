@extends('layout.admin')

@section('content')
    <div class="container">
        <h1>Train Details</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
        <div class="card-header d-flex justify-content-between">
    <strong>{{ $train->trainname }}</strong>
    <a href="{{ route('train.pdf', $train->trainid) }}" class="ml-2">
        <i class="fas fa-download"></i>
    </a>
</div>


            <div class="card-body">
                <p><strong>Number of Compartments:</strong> {{ $train->compartmentnumber }}</p>
                <p><strong>Number of Routes:</strong> {{ $train->updownnumber }}</p>

                @if($train->train_image)
                    <img src="{{ asset('storage/' . $train->train_image) }}" alt="Train Image" style="max-width: 500px;">
                @endif

                <!-- Compartments Table -->
                <h3>Compartments</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Compartment Name</th>
                            <th>Type</th>
                            <th>Total Seats</th>
                            <th>Available Seats</th>
                            <th>Booked Seats</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($train->traincompartments as $compartment)
                            <tr>
                                <td>{{ $compartment->compartmentname }}</td>
                                <td>{{ $compartment->compartmenttype ?? 'N/A' }}</td>
                                <td>{{ $compartment->total_seats }}</td>
                                <td>{{ $compartment->available_seats }}</td>
                                <td>{{ $compartment->booked_seats }}</td>
                                <td>{{ $compartment->price ? '$' . number_format($compartment->price, 2) : 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Routes Table -->
                <h3>Routes</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                        <th>Source</th>
                    <th>Destination</th>
                    <th>Departure Time</th>
                    <th>Arrival Time</th>
                    <th>Departure Date</th>
                    <th>Arrival Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($train->trainupdowns as $updown)
                            <tr>
                            <td>{{ $updown->tsource }}</td>
                    <td>{{ $updown->tdestination }}</td>
                    <td>{{ $updown->tdeptime }}</td>
                    <td>{{ $updown->tarrtime }}</td>
                    <td>{{ $updown->tdepdate }}</td>
                    <td>{{ $updown->tarrdate }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
@endsection
