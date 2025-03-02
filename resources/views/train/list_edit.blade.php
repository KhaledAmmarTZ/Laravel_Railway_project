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
                        <th>S</th>
                        <th>Train Name</th>
                        <th>Source</th>
                        <th>Destination</th>
                        <th>Arrival</th>
                        <th>Departure</th>
                        <th>Availability</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $serial = 1; @endphp <!-- Initialize serial number -->
                    @foreach ($trains as $train)
                        @if ($train->trainupdowns->isNotEmpty())
                            @php
                                $firstRow = true; // Flag to track first row for rowspan
                            @endphp
                            @foreach ($train->trainupdowns as $updown)
                                @php
                                    $arrivalDateTime = \Carbon\Carbon::parse($updown->tarrdate . ' ' . $updown->tarrtime);
                                    $departureDateTime = \Carbon\Carbon::parse($updown->tdepdate . ' ' . $updown->tdeptime);
                                    $currentDate = \Carbon\Carbon::now();

                                    // Check availability for this specific source-destination pair
                                    $isAvailable = $currentDate->lessThanOrEqualTo($departureDateTime);
                                    $availability = $isAvailable ? 'Available' : 'Unavailable';

                                    // Column color when train is unavailable
                                    $columnStyle = $isAvailable ? '' : 'background-color: #ffcccc;';
                                @endphp
                                <tr>
                                    @if ($firstRow)
                                        <td rowspan="{{ $train->trainupdowns->count() }}">{{ $serial }}</td> <!-- Serial Number Column -->
                                        <td rowspan="{{ $train->trainupdowns->count() }}">{{ $train->trainname }}</td>
                                        @php $firstRow = false; @endphp
                                    @endif
                                    <td style="{{ $columnStyle }}">{{ $updown->tsource }}</td>
                                    <td style="{{ $columnStyle }}">{{ $updown->tdestination }}</td>
                                    <td style="{{ $columnStyle }}">{{ $arrivalDateTime->format('d-m-Y h:i A') }}</td>
                                    <td style="{{ $columnStyle }}">{{ $departureDateTime->format('d-m-Y h:i A') }}</td>
                                    <td style="{{ $columnStyle }}">
                                        <span style="color: {{ $isAvailable ? 'green' : 'red' }};">
                                            {{ $availability }}
                                        </span>
                                    </td>
                                    @if ($loop->first)
                                        <td rowspan="{{ $train->trainupdowns->count() }}">
                                            <a href="{{ route('train.edit', $train->trainid) }}" class="btn btn-primary">Edit</a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            @php $serial++; @endphp <!-- Increment serial number -->
                        @else
                            <tr>
                                <td>{{ $serial }}</td> <!-- Serial number for trains with no updowns -->
                                <td>{{ $train->trainname }}</td>
                                <td colspan="6">No data available for this train.</td>
                            </tr>
                            @php $serial++; @endphp <!-- Increment serial number -->
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
