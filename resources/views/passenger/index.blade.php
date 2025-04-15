@extends('layouts.app')
@section('title')
    DashBoard
@endsection

@section('content')
<div class="container py-4">
    @session('msg')
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('msg') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endsession

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-center text-primary mb-0">Available Trains</h1>
        <a href="{{ route('passenger.search') }}" class="btn btn-primary">
            <i class="fas fa-search me-2"></i> Book Tickets
        </a>
    </div>

    <div class="row g-4">
        @foreach ($trains as $train)
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light-blue d-flex justify-content-between align-items-center p-3"
                data-bs-toggle="collapse" 
                data-bs-target="#trainDetails{{ $train['trainid'] }}"
                aria-expanded="false" 
                style="cursor: pointer;">
               <div>
                   <!-- Add route display here -->
                   <div class="route-display mb-2">
                       <span class="fw-bold">{{ $train['tsource'] }}</span>
                       <i class="fas fa-arrow-right mx-2 text-muted"></i>
                       <span class="fw-bold">{{ $train['tdestination'] }}</span>
                   </div>
                   
                   <h3 class="mb-1">{{ $train['trainname'] }}</h3>
                    <div class="d-flex gap-4 text-muted">
                        <span class="d-flex align-items-center gap-2">
                            <i class="fas fa-train"></i>
                            {{ $train['trainid'] }}
                        </span>
                        <span class="d-flex align-items-center gap-2">
                            <i class="fas fa-clock"></i>
                            {{ date('h:i A', strtotime($train['tdeptime'])) }}
                        </span>
                    </div>
                </div>
                <i class="fas fa-chevron-down fs-4"></i>
            </div>


                <div class="collapse" id="trainDetails{{ $train['trainid'] }}">
                    <div class="card-body p-2">
                        <div class="row mb-4 g-2">
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted">Departure</small>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $train['tdepdate'] }}
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-clock"></i>
                                        {{ date('h:i A', strtotime($train['tdeptime'])) }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <small class="text-muted">Arrival</small>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $train['tarrdate'] }}
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-clock"></i>
                                        {{ date('h:i A', strtotime($train['tarrtime'])) }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 d-flex align-items-center justify-content-end gap-3">
                                <button class="btn btn-outline-primary btn-lg px-4 info-btn" 
                                        data-id="{{ $train['trainid'] }}">
                                    <i class="fas fa-info-circle me-2"></i>Details 
                                </button>
                            </div>
                        </div>

                        <h4 class="mb-3">Available Compartments</h4>
                        <div class="row g-4">
                            @foreach ($train['tclass'] as $compartment)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="card-title mb-0">
                                                {{ $compartment->compartmenttype }}
                                            </h5>
                                            <span class="badge bg-primary rounded-pill">
                                                {{ $compartment->{'available_seats_' . ($train['direction'] === 'up' ? 'up' : 'down')} }} Seats
                                            </span>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-end">
                                            <div>
                                                <div class="h4 text-primary mb-0">
                                                    &#2547; {{ number_format($compartment->price, 2) }}
                                                </div>
                                                <small class="text-muted">per seat</small>
                                            </div>
                                            
                                            <form method="GET" action="{{ route('passenger.availableCreate') }}">
                                                @csrf
                                                <input type="hidden" name="trainid" value="{{ $train['trainid'] }}">
                                                <input type="hidden" name="trainname" value="{{ $train['trainname'] }}">
                                                <input type="hidden" name="tarrtime" value="{{ $train['tarrtime'] }}">
                                                <input type="hidden" name="tdeptime" value="{{ $train['tdeptime'] }}">
                                                <input type="hidden" name="tsource" value="{{ $train['tsource'] }}">
                                                <input type="hidden" name="tdestination" value="{{ $train['tdestination'] }}">
                                                <input type="hidden" name="arrdate" value="{{ $train['tarrdate'] }}">
                                                <input type="hidden" name="tclass" value="{{ $compartment->compartmenttype }}">
                                                <input type="hidden" name="price" value="{{ $compartment->price }}">
                                                <input type="hidden" name="available_seats" value="{{ $compartment->total_seats }}">
                                                @php
                                                    $direction = $train['direction'] === 'up' ? 'up' : 'down';
                                                    $availableSeatsField = 'available_seats_' . $direction;
                                                @endphp
                                                <input type="hidden" name="available_seats" value="{{ $compartment->$availableSeatsField }}">
                                                
                                                <input type="hidden" name="direction" value="{{ $train['direction'] }}">

                                                
                                                @if($compartment->{'available_seats_' . ($train['direction'] === 'up' ? 'up' : 'down')} > 0)
                                                    <button type="submit" class="btn btn-primary px-4">
                                                        Book Now
                                                    </button>
                                                @else
                                                    <button class="btn btn-secondary px-4" disabled>
                                                        Sold Out
                                                    </button>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <!-- Bootstrap Modal for Info -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Train Info</h5>
                    <button type="button" class="btn-close" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <span id="info-image"></span>
                    The train ID: <span id="info-id"></span>, is known by the name <span id="info-name"></span>.
                    It consists of multiple compartments designed to accommodate passengers comfortably. 
                    Whether for a short journey or a long-distance trip, this train ensures a smooth and efficient travel 
                    experience.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

    <script>
        document.querySelectorAll("[id^='collapseButton']").forEach(button => {
            button.addEventListener("click", function () {
                var trainId = this.id.replace('collapseButton', '');
                var arrowIcon = document.getElementById("arrowIcon" + trainId);
                if (arrowIcon.classList.contains("fa-chevron-down")) {
                    arrowIcon.classList.remove("fa-chevron-down");
                    arrowIcon.classList.add("fa-chevron-up");
                } else {
                    arrowIcon.classList.remove("fa-chevron-up");
                    arrowIcon.classList.add("fa-chevron-down");
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
        // FETCH INFO
        document.querySelectorAll(".info-btn").forEach(button => {
            button.addEventListener("click", function () {
                let id = this.getAttribute("data-id");
                fetch(`/passenger/${id}/infoTrain`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data)
                        document.getElementById("info-id").textContent = data.trainid;
                        document.getElementById("info-name").textContent = data.trainname;
                        // document.getElementById("info-compartmentnumber").textContent = data.compartmentnumber;
                        document.getElementById("info-image").innerHTML = `<img src="/storage/${data.train_image}" alt="Train Image" class="img-fluid mb-3">`;
                        new bootstrap.Modal(document.getElementById("infoModal")).show();
                    });
                });
            });
        });
    </script>            
@endsection


@section('styles')
<style>
.bg-light-blue {
    background-color: #f0f8ff;
}
.card-header {
    transition: background-color 0.2s ease;
}
.card-header:hover {
    background-color: #e6f3ff !important;
}
.info-btn:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease;
}
</style>
@endsection
