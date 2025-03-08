@extends('layout.admin')

@section('title')
    Admin Profile
@endsection

@section('content')
<div class="w-100 p-4">
    <div class="container-fluid">
        <div class="d-flex" style="gap: 20px;">

            <!-- Left Column (Stacked Cards) -->
            <div class="d-flex flex-column" style="flex: 2.5; gap: 20px;">
                <div class="card p-3">
                    <div class="card-body">
                        <h5 class="card-title">Unavailable Trains</h5>
                        <p class="card-text">
                            Total {{ count($unavailableTrains) }} train(s) are not scheduled, reschedule them 
                            <a href="#" data-toggle="modal" data-target=".bd-example-modal-xl">now!</a>
                        </p>
                    </div>
                </div>

                <div class="card p-3">
                    <div class="card-body">
                        <h5 class="card-title">Extra Card</h5>
                        <p class="card-text">Some more content on the right side.</p>
                    </div>
                </div>
            </div>

            <!-- Middle Column (Main Content) -->
            <div class="card p-3 flex-grow-1">
                <div class="card-body">
                    <h5 class="card-title">Mid</h5>
                    <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                </div>
            </div>

            <!-- Right Column (Single Full-Row Card) -->
            <div class="card p-3" style="flex: 2.5;">
                <div class="card-body">
                    <h5 class="card-title">Calendar</h5>
                    <div id="calendar"></div>
                </div>
            </div>
        </div>

        <!-- Card Deck Section (Cards in Rows) -->
        <div class="d-flex mt-4" style="gap: 20px;">
            <div class="card col-8">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                </div>
            </div>

            <div class="card p-3" style="flex: 2.5;">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="col-12">
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
                                <th style="text-align: center;">Reschedule</th>
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
                                                <a style="position: relative; left: 50%; transform: translateX(-50%); text-align: center;" 
                                                    href="{{ route('train.edit', $train->trainid) }}" 
                                                    class="btn update-btn btn-sm">
                                                    Reschedule
                                                </a>
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
</div>
@endsection
