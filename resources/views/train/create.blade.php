@extends('layout.admin')
@section('title')
    Add Train
@endsection
@section('content')
    <script>
        // Store stations globally
        const stations = @json($stations);

        function generateCompartments() {
            const numCompartment = document.getElementById('numofcompartment').value;
            const compartmentContainer = document.getElementById('compartment-sections');

            // Store existing data
            const existingData = {};
            document.querySelectorAll('[id^="compartments"]').forEach(input => {
                existingData[input.name] = input.value;
            });

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
                const nameValue = existingData[`compartments[${i}][name]`] || '';
                const seatsValue = existingData[`compartments[${i}][seats]`] || '';
                const typeValue = existingData[`compartments[${i}][type]`] || '';

                tableHTML += `
                    <tr>
                        <td>
                            <input type="text" name="compartments[${i}][name]" id="compartments[${i}][name]" class="form-control" value="${nameValue}" required>
                        </td>
                        <td>
                            <input type="number" name="compartments[${i}][seats]" id="compartments[${i}][seats]" class="form-control" value="${seatsValue}" required>
                        </td>
                        <td>
                            <input type="text" name="compartments[${i}][type]" id="compartments[${i}][type]" class="form-control" value="${typeValue}" required>
                        </td>
                    </tr>
                `;
            }

            tableHTML += `</tbody></table>`;
            compartmentContainer.innerHTML = tableHTML;
        }
        }
        document.addEventListener("DOMContentLoaded", function () {
        // Call the function to generate updown sections when the page loads
        generateUpdowns();
    });
    
    
    function generateUpdowns() {
    const updownContainer = document.getElementById('updown-sections');
    
    // Store existing values before updating
    let existingValues = {};
    document.querySelectorAll("#updown-sections input, #updown-sections select").forEach(input => {
        existingValues[input.name] = input.value;
    });

    // Ensure at least one row exists before adding more
    const existingRows = updownContainer.querySelectorAll('tbody tr');
    if (existingRows.length > 0) {
        const lastRow = existingRows[existingRows.length - 1];
        const lastSource = lastRow.querySelector('select[name*="source"]').value;
        const lastDestination = lastRow.querySelector('select[name*="destination"]').value;

        if (!lastSource || !lastDestination) {
            alert("Please select both source and destination before adding a new section.");
            return;
        }
    }

    // If table doesn't exist, create it
    if (!updownContainer.querySelector('table')) {
        updownContainer.innerHTML = `
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
                <tbody></tbody>
            </table>
        `;
    }

    // Get the table body
    const tbody = updownContainer.querySelector('tbody');
    const i = tbody.querySelectorAll('tr').length + 1;

    const rowHTML = `
        <tr>
            <td>
                <select name="updowns[${i}][source]" id="updowns[${i}][source]" class="form-control" onchange="updateDepTime(this, ${i})" required>
                    <option value="">Select Source</option>
                    ${populateStationOptions(existingValues[`updowns[${i}][source]`], i)}
                </select>
            </td>
            <td>
                <select name="updowns[${i}][destination]" id="updowns[${i}][destination]" class="form-control" onchange="updateArrTime(this, ${i})" required>
                    <option value="">Select Destination</option>
                    ${populateStationOptions(existingValues[`updowns[${i}][destination]`])}
                </select>
            </td>
            <td>
                <input type="time" name="updowns[${i}][deptime]" id="deptime_${i}" class="form-control" value="${existingValues[`updowns[${i}][deptime]`] || ''}" required>
            </td>
            <td>
                <input type="time" name="updowns[${i}][arrtime]" id="arrtime_${i}" class="form-control" value="${existingValues[`updowns[${i}][arrtime]`] || ''}" required>
            </td>
            <td>
                <input type="date" name="updowns[${i}][tarrdate]" id="tarrdate_${i}" class="form-control" value="${existingValues[`updowns[${i}][tarrdate]`] || ''}" required>
            </td>
            <td>
                <input type="date" name="updowns[${i}][tdepdate]" id="tdepdate_${i}" class="form-control" value="${existingValues[`updowns[${i}][tdepdate]`] || ''}" required>
            </td>
        </tr>
    `;

    // Append new row without removing existing rows
    tbody.insertAdjacentHTML('beforeend', rowHTML);

    // Restore previously entered values
    document.querySelectorAll("#updown-sections input, #updown-sections select").forEach(input => {
        const name = input.name;
        if (existingValues[name]) {
            input.value = existingValues[name];
        }
    });

    // Disable source options based on previous row's destination
    disableSourceOptions();
}


        // Function to disable source options based on previous section's destination
        function disableSourceOptions() {
            const updowns = document.querySelectorAll("#updown-sections table tbody tr");
            for (let i = 1; i < updowns.length; i++) {
                const previousDestination = updowns[i - 1].querySelector('select[name*="destination"]').value;
                const currentSourceSelect = updowns[i].querySelector('select[name*="source"]');

                // Disable all options
                Array.from(currentSourceSelect.options).forEach(option => {
                    option.disabled = true;
                });

                // Enable the previous updown section's destination as the only source
                Array.from(currentSourceSelect.options).forEach(option => {
                    if (option.value === previousDestination) {
                        option.disabled = false;
                    }
                });
            }
        }

        // Function to populate station options dynamically
        function populateStationOptions(selectedValue = '', currentIndex = 0) {
            let options = '';
            stations.forEach(station => {
                const depTime12Hr = convertTo12HourFormat(station.deeptime);
                const arrTime12Hr = convertTo12HourFormat(station.artime);

                options += `
                    <option value="${station.stationname}" 
                            data-deptime="${station.deeptime}" 
                            data-artime="${station.artime}" 
                            ${station.stationname === selectedValue ? 'selected' : ''}>
                        ${station.stationname})
                    </option>`;
            });
            return options;
        }

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

        // Function to update departure time when source is selected
        function updateDepTime(selectElement, index) {
            const selectedOption = selectElement.selectedOptions[0];
            let depTime = selectedOption.getAttribute('data-deptime');

            // Convert 'HH:MM:SS' to 'HH:MM' if needed
            if (depTime) {
                depTime = depTime.substring(0, 5); // Extract 'HH:MM' from 'HH:MM:SS'
            }

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

        // Function to delete the last updown section
        function deleteUpdown() {
            const updownContainer = document.getElementById('updown-sections');
            const rows = updownContainer.querySelectorAll('tbody tr');
            if (rows.length > 1) {
                rows[rows.length - 1].remove();
                disableSourceOptions();
            }
        }

    </script>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


                <form action="{{ route('train.store') }}" method="POST">
                    @csrf
                    <div class="card text-center" style=" background-color: #f8f9fa; border: 1px solid #ccc;">
                        <div class="card-header text-white" style="background-color: #005F56">
                            Add Train
                        </div>

                        <div class="card-body">
                        <hr style="width: 100%; height: 2px; background-color: transparent; border: none;">
                            <div class="mb-3 d-flex align-items-center">
                                <label for="tname" class="form-label me-3" style="width: 250px; text-align: right;">Train Name : &nbsp;</label>
                                <div class="flex-grow-1">
        <input type="text" name="tname" id="tname" class="form-control w-75" required>
    </div>
                            </div>
                            <hr style="width: 100%; height: 2px; background-color: black; border: none;">
                            <div class="mb-3 d-flex align-items-center">
                                <label for="numofcompartment" class="form-label me-3" style="width: 250px; text-align: right;">Number of Compartments : &nbsp; </label>
                                <div class="flex-grow-1">
                                <input type="number" name="numofcompartment" id="numofcompartment" class="form-control w-75" min="1" onchange="generateCompartments()" required>
                                </div>
                            </div>

                            <div id="compartment-sections"></div> <!-- Dynamic compartments will appear here -->
                            <hr style="width: 100%; height: 2px; background-color: black; border: none;">
                            
                            <div id="updown-sections"></div> <!-- Dynamic updown sections will appear here -->
                            <!-- Updown section -->
                            <input type="hidden" name="updownnumber" id="updownnumber" value="1">

                            <div class="mb-3 d-flex align-items-center">
                                <button type="button" class="btn btn-success" onclick="generateUpdowns()">Add</button>
                                <button type="button" class="btn btn-danger" onclick="deleteUpdown()">Delete</button>
                            </div>
                            
                            <hr style="width: 100%; height: 2px; background-color: black; border: none;">
                            <button type="submit" class="btn search-btn">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
