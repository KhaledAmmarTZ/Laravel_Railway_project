@extends('layout.admin')
@section('title')
    Add Train
@endsection
@section('content')
    <script>
         // Store stations globally
        const stations = @json($stations);

        // Function to dynamically create compartments based on selected number
        function generateCompartments() {
            const numCompartment = document.getElementById('numofcompartment').value;
            const compartmentContainer = document.getElementById('compartment-sections');
            compartmentContainer.innerHTML = '';

            if (numCompartment > 0) {
                let tableHTML = `
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Compartment Name</th>
                                <th>Number of Seats</th>
                                <th>Compartment Type</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                for (let i = 1; i <= numCompartment; i++) {
                    tableHTML += `
                        <tr>
                            <td>
                                <input type="text" name="compartments[${i}][name]" id="compartments[${i}][name]" class="form-control" required>
                            </td>
                            <td>
                                <input type="number" name="compartments[${i}][seats]" id="compartments[${i}][seats]" class="form-control" required>
                            </td>
                            <td>
                                <input type="text" name="compartments[${i}][type]" id="compartments[${i}][type]" class="form-control" required>
                            </td>
                        </tr>
                    `;
                }

                tableHTML += `
                    </tbody>
                </table>
                `;
                compartmentContainer.innerHTML = tableHTML;
            }
        }

        // Function to dynamically create updown sections based on selected number
        function generateUpdowns() {
            const numUpdown = document.getElementById('updownnumber').value;
            const updownContainer = document.getElementById('updown-sections');
            updownContainer.innerHTML = '';

            if (numUpdown > 0) {
                let tableHTML = `
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Source</th>
                                <th>Destination</th>
                                <th>Departure Time</th>
                                <th>Arrival Time</th>
                                <th>Arrival Date</th>
                                <th>Departure Date</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                for (let i = 1; i <= numUpdown; i++) {
                    tableHTML += `
                        <tr>
                            <td>
                                <select name="updowns[${i}][source]" id="updowns[${i}][source]" class="form-control" onchange="updateDepTime(this, ${i})" required>
                                    <option value="">Select Source</option>
                                    ${populateStationOptions()}
                                </select>
                            </td>
                            <td>
                                <select name="updowns[${i}][destination]" id="updowns[${i}][destination]" class="form-control" onchange="updateArrTime(this, ${i})" required>
                                    <option value="">Select Destination</option>
                                    ${populateStationOptions()}
                                </select>
                            </td>
                           <td>
                                <input type="time" name="updowns[${i}][deptime]" id="deptime_${i}" class="form-control" required>
                            </td>
                            <td>
                                <input type="time" name="updowns[${i}][arrtime]" id="arrtime_${i}" class="form-control" required>
                            </td>
                            <td>
                                <input type="date" name="updowns[${i}][tarrdate]" id="updowns[${i}][tarrdate]" class="form-control" required>
                            </td>
                            <td>
                                <input type="date" name="updowns[${i}][tdepdate]" id="updowns[${i}][tdepdate]" class="form-control" required>
                            </td>
                        </tr>
                    `;
                }

                tableHTML += `
                    </tbody>
                </table>
                `;
                updownContainer.innerHTML = tableHTML;
            }
        }


    // Function to validate and set the time in HH:MM:SS format
    function convertTo24HourFormatWithSeconds(time) {
        const timeParts = time.match(/(\d+):(\d+):(\d+)\s*(am|pm)/i);
        if (!timeParts) return ''; // Return empty if format is invalid

        let hours = parseInt(timeParts[1], 10);
        const minutes = timeParts[2];
        const seconds = timeParts[3];
        const ampm = timeParts[4].toLowerCase();

        if (ampm === 'pm' && hours < 12) hours += 12;
        if (ampm === 'am' && hours === 12) hours = 0;

        return `${hours.toString().padStart(2, '0')}:${minutes}:${seconds}`;
    }

    // Function to generate station options dynamically
// Function to convert 24-hour time format to 12-hour format
function convertTo12HourFormat(time) {
    let [hours, minutes] = time.split(":");
    hours = parseInt(hours);
    let period = hours >= 12 ? "PM" : "AM";
    if (hours > 12) {
        hours -= 12;
    } else if (hours === 0) {
        hours = 12; // Midnight case
    }
    return `${hours}:${minutes} ${period}`;
}

// Function to generate station options dynamically with times in 12-hour format
function populateStationOptions() {
    let options = '';
    stations.forEach(station => {
        const depTime12Hr = convertTo12HourFormat(station.deeptime);
        const arrTime12Hr = convertTo12HourFormat(station.artime);
        
        options += `
            <option value="${station.stationname}" 
                    data-deptime="${station.deeptime}" 
                    data-artime="${station.artime}">
                ${station.stationname} (Departure Time: ${depTime12Hr} - Arrival Time: ${arrTime12Hr})
            </option>`;
    });
    return options;
}


    // Function to update departure time when source is selected
function updateDepTime(selectElement, index) {
    const selectedOption = selectElement.selectedOptions[0];
    let depTime = selectedOption.getAttribute('data-deptime');
    
    // Convert 'HH:MM:SS' to 'HH:MM' if needed
    if (depTime) {
        depTime = depTime.substring(0, 5); // Extract 'HH:MM' from 'HH:MM:SS'
    }

    document.getElementById(`deptime_${index}`).value = depTime;
    
    // Prevent the same source and destination selection
    const sourceValue = selectElement.value;
    const destinationSelect = document.getElementById(`updowns[${index}][destination]`);
    
    // Enable all options first
    Array.from(destinationSelect.options).forEach(option => {
        option.disabled = false;
    });

    // Disable the selected source station in the destination dropdown
    Array.from(destinationSelect.options).forEach(option => {
        if (option.value === sourceValue) {
            option.disabled = true;
        }
    });
}

// Function to update arrival time when destination is selected
function updateArrTime(selectElement, index) {
    const selectedOption = selectElement.selectedOptions[0];
    let arrTime = selectedOption.getAttribute('data-artime');
    
    // Convert 'HH:MM:SS' to 'HH:MM' if needed
    if (arrTime) {
        arrTime = arrTime.substring(0, 5); // Extract 'HH:MM' from 'HH:MM:SS'
    }

    document.getElementById(`arrtime_${index}`).value = arrTime;
    
    // Prevent the same source and destination selection
    const destinationValue = selectElement.value;
    const sourceSelect = document.getElementById(`updowns[${index}][source]`);

    // Disable the selected destination station in the source dropdown
    Array.from(sourceSelect.options).forEach(option => {
        option.disabled = false;
    });

    // Disable the selected destination station in the source dropdown
    Array.from(sourceSelect.options).forEach(option => {
        if (option.value === destinationValue) {
            option.disabled = true;
        }
    });
}

    </script>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


<div class="container">
    <div class="row">
            <div class="col-md-12">
                <form action="{{ route('train.store') }}" method="POST">
                    @csrf
                    <div class="card text-center" style="width: 100%; background-color: #f8f9fa; border: 1px solid #ccc;">
                        <div class="card-header text-white" style="background-color: #005F56">
                            Add Train
                        </div>
                        <div class="card-body">
                            <div class="mb-3 d-flex align-items-center">
                                <label for="tname" class="form-label me-3" style="width: 350px; text-align: right;">Train Name : &nbsp;</label>
                                <input type="text" name="tname" id="tname" class="form-control flex-grow-1" required>
                            </div>
                            <hr style="width: 100%; height: 2px; background-color: black; border: none;">
                            <div class="mb-3 d-flex align-items-center">
                                <label for="numofcompartment" class="form-label me-3" style="width: 350px; text-align: right;">Number of Compartments : &nbsp; </label>
                                <input type="number" name="numofcompartment" id="numofcompartment" class="form-control flex-grow-1" min="1" onchange="generateCompartments()" required>
                            </div>
                            
                            <div id="compartment-sections"></div> <!-- Dynamic compartments will appear here -->
                            <hr style="width: 100%; height: 2px; background-color: black; border: none;">
                            <div class="mb-3 d-flex align-items-center">
                                <label for="updownnumber" class="form-label me-3" style="width: 350px; text-align: right;">Number of Schedules : &nbsp;</label>
                                <input type="number" name="updownnumber" id="updownnumber" class="form-control flex-grow-1" min="1" onchange="generateUpdowns()" required>
                            </div>

                            <div id="updown-sections"></div> <!-- Dynamic updown sections will appear here -->
                            <hr style="width: 100%; height: 2px; background-color: black; border: none;">
                            <button type="submit" class="btn search-btn">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
