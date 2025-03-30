@extends('layout.admin')

@section('content')

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <style>
        th, td {
            font-weight: bold;
            color: white;
        }
        h3 {
            font-weight: bold;
            color: white;
            text-align: center;
        }
    </style>

    <div class="card w-100" style="min-width: 320px; background: rgba(255, 255, 255, 0.3); border: 1px solid #ccc;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #005F56; color: white;">
            <strong>{{ $train->trainname }}</strong>
            <a href="{{ route('train.pdf', $train->trainid) }}" class="ml-2">
                <i class="fas fa-download text-white"></i>
            </a>
        </div>

        <div class="card-body p-3" style="padding-top: 10px; padding-left: 10px; padding-right: 10px;">
            <div class="row">
                <div class="col-md-4 col-12 mb-2">
                    <p class="fw-bold text-white"><strong>Train Name:</strong> {{ $train->trainname }}</p>
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <p class="fw-bold text-white"><strong>Number of Compartments:</strong> {{ $train->compartmentnumber }}</p>
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <p class="fw-bold text-white"><strong>Number of Routes:</strong> {{ $train->updownnumber }}</p>
                </div>
            </div>

            @if($train->train_image)
                <div class="text-center mb-3">
                    <img src="{{ asset('storage/' . $train->train_image) }}" alt="Train Image" class="img-fluid" style="max-width: 500px;">
                </div>
            @endif
            <hr style="width: 100%; height: 2px; background-color: white; border: none;">
            <!-- Compartments Table -->
            <h3>Compartments</h3>
            <div class="table-responsive">
                <table class="table table-bordered text-white">
                    <thead class="bg-dark">
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
            </div>
            <hr style="width: 100%; height: 2px; background-color: white; border: none;">
            <!-- Routes Table -->
            <h3>Routes</h3>
            <div class="table-responsive">
                <table class="table table-bordered text-white">
                    <thead class="bg-dark">
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
