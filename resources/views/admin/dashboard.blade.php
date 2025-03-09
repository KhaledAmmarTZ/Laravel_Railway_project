@extends('layout.admin')

@section('title')
    Admin Profile
@endsection

@section('content')
<div class="w-100 p-4">
    <div class="container-fluid">
        <div class="d-flex" style="gap: 20px;">

            <!-- Left Column (Stacked Cards) -->
            <div class="d-flex flex-column" style="flex: 1.5; gap: 20px;">
                <div class="card p-3">
                    <div class="card-body">
                        <h5 class="card-title text-center">Unavailable Trains</h5>
                        <div style="width: 200px; height: 200px; margin: auto;">
                            <canvas id="unavailableTrainsPieChart"></canvas>
                        </div>

                        <!-- Indicators for Available and Unavailable Trains -->
                        <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                            <div>
                                <span style="display: inline-block; width: 20px; height: 20px; border-radius: 25%; background-color: #36A2EB;"></span>
                                Available Trains: <span id="availableTrainsValue">{{ $availableTrains }}</span>
                            </div>
                            <div>
                                <span style="display: inline-block; width: 20px; height: 20px; border-radius: 25%; background-color: #FF6384;"></span>
                                Unavailable Trains: <span id="unavailableTrainsValue">{{ $unavailableTrains->count() }}</span>
                            </div>
                        </div>
                        
                        <p class="card-text">
                            Total {{ count($unavailableTrains) }} train(s) are not scheduled, reschedule them 
                            <a href="#" data-toggle="modal" data-target=".bd-example-modal-xl">now!</a>
                        </p>
                    </div>
                </div>

                <div class="card p-3 flex-grow-1">
                    <div class="card-body">
                        <h5 class="card-title">User Count</h5>
                        <p class="card-text">The number of users currently in the system:</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span id="userCount" class="h4 text-primary">0</span> <!-- Placeholder for user count -->
                        </div>
                    </div>
                </div>

            </div>

            <!-- Middle Column (Main Content) -->
            <div class="card p-3">
                <div class="card-body">
                    <h5 class="card-title text-center">Monthly Data Chart</h5>
                    <div style="display: flex; justify-content: center; overflow-x: auto;">
                        <div style="width: 700px; height: 400px;">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                    <p class="card-text text-center"><small class="text-muted">Last updated 3 mins ago</small></p>
                </div>
            </div>
            
            <!-- Right Column (Single Full-Row Card) -->
            <div class="card p-3" style="flex: 1.5; max-width: 250px; width: 100%;"> <!-- Adjust flex and added max-width -->
              <div class="card-body">
                  <div class="calendar">
                      <div class="calendar-header">
                          <span class="month-picker" id="month-picker">February</span>
                          <div class="year-picker">
                              <span class="year-change" id="prev-year">
                                  <pre><</pre>
                              </span>
                              <span id="year">2021</span>
                              <span class="year-change" id="next-year">
                                  <pre>></pre>
                              </span>
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

        <!-- Card Deck Section (Cards in Rows) -->
        <div class="d-flex mt-4" style="gap: 20px;">
            <div class="card col-8">
                <div class="card-body">
                    <h5 class="card-title">Customer Feedback</h5>
                    <!-- Recent Feedback List -->
                    <div id="feedbackList">
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


            <div class="card p-3" style="flex: 2.5;">
                <div class="card-body">
                    <h5 class="card-title">Customer Reports</h5>
                    <!-- Recent Reports List -->
                    <div id="reportList">
                        <div class="report-item mb-3">
                            <p class="card-text" style="font-size: 0.7rem;"><strong>Report by John Doe:</strong> "The product quality is excellent, but delivery took longer than expected."</p>
                            <p class="card-text" style="font-size: 0.6rem;"><small class="text-muted">Last updated 10 mins ago</small></p>
                        </div>
                        <div class="report-item">
                            <p class="card-text" style="font-size: 0.7rem;"><strong>Report by Jane Smith:</strong> "The support team was helpful in resolving my issue quickly."</p>
                            <p class="card-text" style="font-size: 0.6rem;"><small class="text-muted">Last updated 15 mins ago</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        text: 'Days of the Month'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Total Number'
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
