@extends('layout.admin')

@section('title')
    Admin Profile
@endsection

@section('content')
<style>
    .blurry-card {
    position: relative;
    background-color: rgba(248, 249, 250, 0.5); /* Add a semi-transparent background */
    backdrop-filter: blur(2px); /* Apply the blur effect */
    }
    .card-text{
        font-size: 0.8rem;
        color:rgb(255, 255, 255);
    }
    .card-title{
        color:rgb(255, 255, 255);
    }
</style>
<div class="container-fluid">
    <div class="row g-3">
        <!-- Left Column (Stacked Cards) -->
        <div class="col-lg-4 col-md-6" style="display: flex; flex-direction: column; gap: 1rem;">
            <div class="card p-3 blurry-card" style="border: none;">
                <div class="card-body">
                    <h5 class="card-title text-center">Unavailable Trains</h5>
                    <div style="width: 200px; height: 200px; margin: auto;">
                        <canvas id="unavailableTrainsPieChart"></canvas>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            <span class="indicator" style="background-color: #36A2EB;"></span>
                            Available Trains: <span id="availableTrainsValue">{{ $availableTrains }}</span>
                        </div>
                        <div>
                            <span class="indicator" style="background-color: #FF6384;"></span>
                            Unavailable Trains: <span id="unavailableTrainsValue">{{ $unavailableTrains->count() }}</span>
                        </div>
                    </div>
                    <p class="card-text">
                        Total {{ count($unavailableTrains) }} train(s) are not scheduled, reschedule them 
                        <a href="#" data-toggle="modal" data-target=".bd-example-modal-xl">now!</a>
                    </p>
                </div>
            </div>

            <div class="card p-3 blurry-card" style="border: none;">
                <div class="card-body">
                    <h5 class="card-title">User  Count</h5>
                    <p class="card-text">The number of users currently in the system:</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span id="userCount" class="h4 text-primary">0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle Column (Main Content) -->
        <div class="col-lg-6 col-md-12">
            <div class="card p-3 blurry-card" style="border: none;">
                <div class="card-body">
                    <h5 class="card-title text-center">Monthly Data Chart</h5>
                    <div class="d-flex justify-content-center overflow-auto">
                        <div style="width: 100%; max-width: 700px; height: 400px;">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                    <p class="card-text text-center"><small class="text-muted">Last updated 3 mins ago</small></p>
                </div>
            </div>
        </div>

        <!-- Right Column (Single Full-Row Card) -->
        <div class="col-lg-2 col-md-12">
            <div class="card p-3 blurry-card" style="border: none; height: 100%; max-height: 100%;">
                <div class="card-body">
                    <div class="calendar">
                        <div class="calendar-header">
                            <span class="month-picker" id="month-picker">February</span>
                            <div class="year-picker">
                                <span class="year-change" id="prev-year"><pre><</pre></span>
                                <span id="year">2021</span>
                                <span class="year-change" id="next-year"><pre>></pre></span>
                            </div>
                        </div>
                        <div class="calendar-body">
                            <div class="calendar-week-day">
                                <div>Sun</div>
                                <div>Mon</div>
                                <div>Tue</div>
                                <div>Wed</div>
                                <div>Thu</div>
                                <div>Fri</div>
                                <div>Sat</div>
                            </div>
                            <div class="calendar-days"></div>
                        </div>
                        <div class="month-list"></div>
                    </div>
                </div>
                <p class="card-text"><strong>John Doe:</strong> "Great service, very satisfied!"</p>
                <p class="card-text"><small class="text-muted">Last updated 2 mins ago</small></p>
            </div>
        </div>
    </div>

    <!-- Card Deck Section (Cards in Rows) -->
    <div class="row g-3 mt-4">
        <div class="col-lg-8 col-md-12">
            <div class="card blurry-card" style="border: none; height: 100%; max-height: 100%;">
                <div class="card-body">
                    <h5 class="card-title" style="margin-top: 15px; margin-left: 20px; text-align:center;">Customer Feedback</h5>
                    <!-- Recent Feedback List -->
                    <div id="feedbackList" style="margin-top: 15px; margin-left: 20px;">
                        <div class="feedback-item mb-3">
                            <p class="card-text"><strong>John Doe:</strong> "Great service, very satisfied!"</p>
                            <p class="card-text"><small class="text-muted">Last updated 2 mins ago</small></p>
                        </div>
                        <div class="feedback-item">
                            <p class="card-text"><strong>Jane Smith:</strong> "The website was easy to navigate, I will definitely return."</p>
                            <p class="card-text"><small class="text-muted">Last updated 5 mins ago</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12">
            <div class="card blurry-card" style="border: none; height: 100%; max-height: 100%;">
                <div class="card-body">
                    <h5 class="card-title" style="margin-top: 15px; margin-left: 20px; text-align: center;">Customer Reports</h5>
                    <div id="reportList" style="margin-top: 15px; margin-left: 20px;">
                        <div class="report-item mb-3">
                            <p class="card-text" style="font-size: 0.7rem;"><strong>Report by John Doe:</strong> "The product quality is excellent, but delivery took longer than expected."</p>
                            <p class="card-text" style="font-size: 0.6rem;"><small class="text-muted">Last updated 10 mins ago</small></p>
                        </div>
                        <div class="report-item">
                            <p class="card-text" style="font-size: 0.7rem;"><strong>Report by Jane Smith:</strong> "The support team was helpful in resolving my issue quickly."</p>
                            <p class="card-text" style="font-size: 0.6rem;"><small class="text-muted">Last updated 15 mins ago</small></p>
                        </div>
                        <div class="report-item mb-3">
                            <p class="card-text" style="font-size: 0.7rem;"><strong>Report by John Doe:</strong> "The product quality is excellent, but delivery took longer than expected."</p>
                            <p class="card-text" style="font-size: 0.6rem;"><small class="text-muted">Last updated 10 mins ago</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Unavailable Trains -->
    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Unavailable Trains</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Get current month and year
    const currentMonth = new Date().getMonth(); 
    const currentYear = new Date().getFullYear();

    // Get the number of days in the current month
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate(); 

    // X-axis: Days of the current month
    const labels = Array.from({ length: daysInMonth }, (_, i) => i + 1); 

    // Y-axis: Example total numbers (you can replace with real data)
    const data = labels.map(() => Math.floor(Math.random() * 100)); 

    // Monthly Data Chart (Line chart)
    const ctxLine = document.getElementById('monthlyChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Number Per Day',
                data: data,
                borderColor: 'blue',
                backgroundColor: 'rgba(0, 0, 255, 0.2)',
                borderWidth: 1,
                fill: true,
                pointRadius: 2,
                pointBackgroundColor: 'blue'
            }]
        },
        options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Days of the Month',
                    color: 'white' // Set the x-axis title text color to white
                },
                ticks: {
                    color: 'white' // Set x-axis ticks text color to white
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Total Number',
                    color: 'white' // Set the y-axis title text color to white
                },
                ticks: {
                    color: 'white' // Set y-axis ticks text color to white
                }
            }
        },
        plugins: {
            legend: {
                labels: {
                    color: 'white' // Set legend text color to white
                }
            }
        }
    }
});

// Get the number of available and unavailable trains from the Blade variables
const availableTrains = {{ $availableTrains }};
const unavailableTrains = {{ $unavailableTrains->count() }};

// Unavailable Trains Pie Chart
const ctxPie = document.getElementById('unavailableTrainsPieChart').getContext('2d');
const chart = new Chart(ctxPie, {
    type: 'doughnut',
    data: {

        datasets: [{
            data: [availableTrains, unavailableTrains], // Use dynamic values
            backgroundColor: ['#36A2EB', '#FF6384'],
            hoverBackgroundColor: ['#36A2EB', '#FF6384']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top', // 'top', 'left', 'bottom', 'right'
                align: 'center', // You can change alignment if needed
                labels: {
                    boxWidth: 20, // Width of the colored box in the legend
                    padding: 15, // Space between the label and the box
                    usePointStyle: true // Make the labels appear like small circles, matching the chart colors
                }
            }
        }
    }
});

// Update the indicators dynamically when the chart is loaded
document.getElementById('availableTrainsValue').innerText = availableTrains;
document.getElementById('unavailableTrainsValue').innerText = unavailableTrains;

</script>
@endsection