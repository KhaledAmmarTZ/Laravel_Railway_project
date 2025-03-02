@extends('layout.admin')

@section('title')
    Admin Profile
@endsection

@section('content')
    <div class="w-100 p-4">
        <!-- Top Section: Profile Pic & User Info -->
        <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
            <div class="d-flex align-items-center">
                <img src="{{ auth()->guard('admin')->user()->admin_image 
                    ? asset('storage/' . auth()->guard('admin')->user()->admin_image) 
                    : asset('images/default-profile.png') }}" 
                    class="border custom-rounded img-fluid" width="220" height="220" 
                    alt="Profile Picture">
                <div class="ms-4" style="margin-left: 30px;">
                    <!-- Display the logged-in super admin's name -->
                    <h3 class="mb-1">{{ auth()->guard('admin')->user()->name }}</h3>
                    
                    <!-- Display the logged-in super admin's role -->
                    <p class="text-muted mb-1">{{ ucfirst(auth()->guard('admin')->user()->role) }}</p>
                    
                    <!-- Display the logged-in super admin's email -->
                    <p class="text-muted mb-0">{{ auth()->guard('admin')->user()->email }}</p>
                </div>
            </div>
        </div>

        <hr>

        <!-- Additional Details Section -->
        <div class="row">
            <div class="col-md-6">
                <!-- Display super admin's phone number -->
                <p><strong>Phone:</strong> {{ auth()->guard('admin')->user()->phoneNumber }}</p>
                
                <!-- Display super admin's date of birth -->
                <p><strong>Date of Birth:</strong> {{ \Carbon\Carbon::parse(auth()->guard('admin')->user()->date_of_birth)->format('M d, Y') }}</p>
            </div>
            <div class="col-md-6">
                <!-- Display super admin's NID -->
                <p><strong>NID:</strong> {{ auth()->guard('admin')->user()->admin_nid }}</p>
                
                <!-- Display super admin's place -->
                <p><strong>Place:</strong> {{ auth()->guard('admin')->user()->place }}</p>
            </div>
        </div>

        <hr style="width: 100%; height: 2px; background-color: black; border: none;">

        <!-- Display the Unavailable Train Section -->
        <div class="row">
            <div class="col-12">
                <h4 class="mb-3" style="text-align: center;">Unavailable Trains</h4>

                @if($unavailableTrains->isEmpty())
                    <p>No unavailable trains found.</p>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Train Name</th>
                                <th>Departure Time</th>
                                <th>Arrival Time</th>
                                <th>Source</th>
                                <th>Destination</th>
                                <th style="text-align: center;" >Reschedule</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($unavailableTrains as $train)
                                @foreach($train->trainupdowns as $index => $updown)
                                    @php
                                        $departureTime = \Carbon\Carbon::parse($updown->tdepdate . ' ' . $updown->tdeptime);
                                        $status = $departureTime->isPast() ? 'Unavailable' : 'Available';
                                    @endphp

                                    @if($status === 'Unavailable')
                                        <tr>
                                            <td>{{ $train->trainname }}</td>
                                            <td>{{ \Carbon\Carbon::parse($updown->tdepdate)->format('d-m-Y') }} 
                                                {{ \Carbon\Carbon::parse($updown->tdeptime)->format('h:i A') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($updown->tarrdate)->format('d-m-Y') }} 
                                                {{ \Carbon\Carbon::parse($updown->tarrtime)->format('h:i A') }}</td>
                                            <td>{{ $updown->tsource }}</td>
                                            <td>{{ $updown->tdestination }}</td>
                                            <td>
                                                <!-- Reschedule Button -->
                                                <a style="position: relative; left: 50%; transform: translateX(-50%); text-align: center;" href="{{ route('train.edit', $train->trainid) }}" class="btn update-btn btn-sm">Reschedule</a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection
