@extends('layout.admin')
@section('title')
Add Train
@endsection
@section('content')
<style>
    #updown-sections table {
        border-collapse: collapse;
        width: 100%;
    }

    #updown-sections th, 
    #updown-sections td {
        border: 3px solid black;
        padding: 8px;
        text-align: center;
    }

    #updown-sections th {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    #compartment-sections table {
        border-collapse: collapse;
        width: 100%;
    }

    #compartment-sections th, 
    #compartment-sections td {
        border: 3px solid black;
        padding: 8px;
        text-align: center;
    }

    #compartment-sections th {
        background-color: #f8f9fa;
        font-weight: bold;
    }
</style>
<script>
const stations = @json($stations);

function generateCompartments() {
    let numCompartment = document.getElementById('numofcompartment').value || 1;
    numCompartment = Math.max(1, numCompartment);

    const compartmentContainer = document.getElementById('compartment-sections');

    const existingData = {};
    document.querySelectorAll('[id^="compartments"]').forEach(input => {
        existingData[input.name] = input.value;
    });

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

    attachInputListeners(); 
    showCompartmentData();  
}

function attachInputListeners() {
    document.querySelectorAll('[id^="compartments"]').forEach(input => {
        input.addEventListener('input', showCompartmentData); 
    });
}

function showCompartmentData() {
    let displayHTML = `
        <h3>Entered Compartment Data:</h3>
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

    let numCompartment = document.getElementById('numofcompartment').value || 1;
    numCompartment = Math.max(1, numCompartment);

    for (let i = 1; i <= numCompartment; i++) {
        const name = document.getElementById(`compartments[${i}][name]`)?.value || '';
        const seats = document.getElementById(`compartments[${i}][seats]`)?.value || '';
        const type = document.getElementById(`compartments[${i}][type]`)?.value || '';

        if (name || seats || type) {
            displayHTML += `
                <tr>
                    <td>${name}</td>
                    <td>${seats}</td>
                    <td>${type}</td>
                </tr>
            `;
        }
    }

    displayHTML += `</tbody></table>`;
    document.getElementById('compartment-data-display').innerHTML = displayHTML;
}

window.onload = function () {
    generateCompartments();
};

document.addEventListener("DOMContentLoaded", function () {
    generateUpdowns();
});

function generateUpdowns() {
    const updownContainer = document.getElementById('updown-sections');

    let existingValues = {};
    document.querySelectorAll("#updown-sections input, #updown-sections select").forEach(input => {
        existingValues[input.name] = input.value;
    });

    const existingRows = updownContainer.querySelectorAll('tbody tr');
    let lastArrivalTime = "";

    if (existingRows.length > 0) {
        const lastRow = existingRows[existingRows.length - 1];
        const lastSource = lastRow.querySelector('select[name*="source"]').value;
        const lastDestination = lastRow.querySelector('select[name*="destination"]').value;
        lastArrivalTime = lastRow.querySelector('input[name*="arrtime"]').value; 

        if (!lastSource || !lastDestination) {
            alert("Please select both source and destination before adding a new section.");
            return;
        }
    }

    if (!updownContainer.querySelector('table')) {
        updownContainer.innerHTML = `
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Source</th>
                        <th>Destination</th>
                        <th>Departure Date</th>
                        <th>Arrival Date</th>
                        <th>Departure Time</th>
                        <th>Arrival Time</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        `;
    }

    const tbody = updownContainer.querySelector('tbody');
    const i = tbody.querySelectorAll('tr').length + 1;
    let minDepDate = "";

    if (existingRows.length > 0) {
        minDepDate = existingRows[existingRows.length - 1].querySelector('input[name*="tarrdate"]').value;
    }

    const rowHTML = `
        <tr>
            <td>
                <select name="updowns[${i}][source]" id="updowns[${i}][source]" class="form-control updown-input source-select" data-index="${i}" required>
                    <option value="">Select Source</option>
                    ${populateStationOptions(existingValues[`updowns[${i}][source]`], i)}
                </select>
            </td>
            <td>
                <select name="updowns[${i}][destination]" id="updowns[${i}][destination]" class="form-control updown-input destination-select" data-index="${i}" required>
                    <option value="">Select Destination</option>
                    ${populateStationOptions(existingValues[`updowns[${i}][destination]`])}
                </select>
            </td>

            <td>
                <input type="date" name="updowns[${i}][tdepdate]" id="tdepdate_${i}" class="form-control updown-input depdate" 
                    value="${existingValues[`updowns[${i}][tdepdate]`] || ''}" min="${minDepDate}" required>
            </td>
            <td>
                <input type="date" name="updowns[${i}][tarrdate]" id="tarrdate_${i}" class="form-control updown-input arrdate" 
                    value="${existingValues[`updowns[${i}][tarrdate]`] || ''}" required>
            </td>

            <td>
                <input type="time" name="updowns[${i}][deptime]" id="deptime_${i}" class="form-control updown-input" value="${existingValues[`updowns[${i}][deptime]`] || ''}" required>
            </td>
            <td>
                <input type="time" name="updowns[${i}][arrtime]" id="arrtime_${i}" class="form-control updown-input" value="${existingValues[`updowns[${i}][arrtime]`] || ''}" required>
            </td>
        </tr>                            
    `;

    tbody.insertAdjacentHTML('beforeend', rowHTML);
    disablePreviousDestinations();
    validateDates();

    document.querySelectorAll("#updown-sections input, #updown-sections select").forEach(input => {
        const name = input.name;
        if (existingValues[name]) {
            input.value = existingValues[name];
        }
    });

    attachUpdownInputListeners();
    disableSourceOptions();
    lockSourceSelection();
    updateRouteDisplay();
    showUpdownData();

    if (lastArrivalTime) {
        setMinDepartureTime(i, lastArrivalTime);
    }

    preventSameSourceDestination(i);
}

function preventSameSourceDestination(index) {
    const sourceSelect = document.getElementById(`updowns[${index}][source]`);
    const destinationSelect = document.getElementById(`updowns[${index}][destination]`);

    if (!sourceSelect || !destinationSelect) return;

    function updateDestinationOptions() {
        const selectedSource = sourceSelect.value;

        destinationSelect.querySelectorAll("option").forEach(option => {
            option.disabled = false;
        });

        if (selectedSource) {
            destinationSelect.querySelector(`option[value="${selectedSource}"]`).disabled = true;
        }
    }

    function updateSourceOptions() {
        const selectedDestination = destinationSelect.value;

        sourceSelect.querySelectorAll("option").forEach(option => {
            option.disabled = false;
        });

        if (selectedDestination) {
            sourceSelect.querySelector(`option[value="${selectedDestination}"]`).disabled = true;
        }
    }

    sourceSelect.addEventListener("change", updateDestinationOptions);
    destinationSelect.addEventListener("change", updateSourceOptions);
}

function setMinDepartureTime(index, lastArrivalTime) {
    const deptimeInput = document.getElementById(`deptime_${index}`);

    if (!deptimeInput || !lastArrivalTime) return;

    let [hours, minutes] = lastArrivalTime.split(":").map(Number);
    
    // Add 20 minutes for min time
    let minMinutes = minutes + 20;
    let minHours = hours;
    if (minMinutes >= 60) {
        minMinutes -= 60;
        minHours += 1;
    }

    // Add 30 minutes for max time
    let maxMinutes = minutes + 30;
    let maxHours = hours;
    if (maxMinutes >= 60) {
        maxMinutes -= 60;
        maxHours += 1;
    }

    const minTime = `${String(minHours).padStart(2, '0')}:${String(minMinutes).padStart(2, '0')}`;
    const maxTime = `${String(maxHours).padStart(2, '0')}:${String(maxMinutes).padStart(2, '0')}`;

    deptimeInput.setAttribute("min", minTime);
    deptimeInput.setAttribute("max", maxTime);

    deptimeInput.addEventListener("input", function () {
        const selectedTime = this.value;
        if (selectedTime < minTime || selectedTime > maxTime) {
            alert(`Departure time must be between ${minTime} and ${maxTime}`);
            this.value = minTime; 
        }
    });
}

function validateDates() {
    document.querySelectorAll(".depdate, .arrdate").forEach(input => {
        input.addEventListener("change", function () {
            const row = this.closest("tr");
            const depDateInput = row.querySelector(".depdate");
            const arrDateInput = row.querySelector(".arrdate");

            const depDate = new Date(depDateInput.value);
            const arrDate = new Date(arrDateInput.value);

            if (arrDate < depDate) {
                alert("Arrival date must be the same or after the departure date.");
                arrDateInput.value = "";
            }

            const nextRow = row.nextElementSibling;
            if (nextRow) {
                const nextDepDateInput = nextRow.querySelector(".depdate");
                if (nextDepDateInput) {
                    nextDepDateInput.setAttribute("min", arrDateInput.value);
                }
            }
        });
    });
}


function attachUpdownInputListeners() {
    document.querySelectorAll('.updown-input').forEach(input => {
        input.addEventListener('input', showUpdownData);  
        input.addEventListener('change', showUpdownData); 
    });
}

function showUpdownData() {
    const updownContainer = document.getElementById('updown-sections');
    const tbody = updownContainer.querySelector('tbody');
    const rows = tbody.querySelectorAll('tr');

    let displayHTML = `
        <h3>Entered Train Route Data:</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Source</th>
                    <th>Destination</th>
                    <th>Departure Date</th>
                    <th>Arrival Date</th>
                    <th>Departure Time</th>
                    <th>Arrival Time</th>
                </tr>
            </thead>
            <tbody>
    `;

    rows.forEach(row => {
        const source = row.querySelector('select[name*="source"]').value;
        const destination = row.querySelector('select[name*="destination"]').value;
        const depDate = row.querySelector('input[name*="tdepdate"]').value;
        const arrDate = row.querySelector('input[name*="tarrdate"]').value;
        const depTime = row.querySelector('input[name*="deptime"]').value;
        const arrTime = row.querySelector('input[name*="arrtime"]').value;

        if (source || destination || depDate || arrDate || depTime || arrTime) { 
            displayHTML += `
                <tr>
                    <td>${source}</td>
                    <td>${destination}</td>
                    <td>${depDate}</td>
                    <td>${arrDate}</td>
                    <td>${depTime}</td>
                    <td>${arrTime}</td>
                </tr>
            `;
        }
    });

    displayHTML += `</tbody></table>`;
    document.getElementById('updown-data-display').innerHTML = displayHTML;
}

function disableSourceOptions() {
    const updowns = document.querySelectorAll("#updown-sections table tbody tr");
    for (let i = 1; i < updowns.length; i++) {
        const previousDestination = updowns[i - 1].querySelector('select[name*="destination"]').value;
        const currentSourceSelect = updowns[i].querySelector('select[name*="source"]');

        Array.from(currentSourceSelect.options).forEach(option => {
            option.disabled = true;
        });
        Array.from(currentSourceSelect.options).forEach(option => {
            if (option.value === previousDestination) {
                option.disabled = false;
            }
        });
    }
}

function populateStationOptions(selectedValue = '', currentIndex = 0) {
    let options = '';
    stations.forEach(station => {

        options += `
                                    <option value="${station.stationname}" 
                                            ${station.stationname === selectedValue ? 'selected' : ''}>
                                        ${station.stationname}
                                    </option>`;
    });
    return options;
}

function convertTo12HourFormat(time) {
    let [hours, minutes] = time.split(":");
    hours = parseInt(hours);
    let period = hours >= 12 ? "PM" : "AM";
    if (hours > 12) {
        hours -= 12;
    } else if (hours === 0) {
        hours = 12;
    }
    return `${hours}:${minutes} ${period}`;
}

function lockSourceSelection() {
    document.querySelectorAll("#updown-sections table tbody tr").forEach((row, index) => {
        if (index > 0) { 
            const sourceSelect = row.querySelector('select[name*="source"]');

            sourceSelect.addEventListener('change', function (event) {
                if (this.dataset.selected) {
                    showCustomModal(this, event, 'source');
                } else {
                    this.dataset.selected = this.value;
                }
            });
        }
    });
}

function disablePreviousDestinations() {
    const updowns = document.querySelectorAll("#updown-sections table tbody tr");

    updowns.forEach((row, index) => {
        if (index < updowns.length - 1) { 
            const destinationSelect = row.querySelector('select[name*="destination"]');
            
            destinationSelect.addEventListener('change', function(event) {
                showCustomModal(destinationSelect, event, 'destination');
            });

            destinationSelect.dataset.previousValue = destinationSelect.value;
        }
    });
}

function showCustomModal(selectElement, event, type) {
    const modal = document.getElementById('custom-alert');
    const alertMessage = document.getElementById('alert-message');
    const alertYesButton = document.getElementById('alert-yes');
    const alertCancelButton = document.getElementById('alert-cancel');

    alertMessage.textContent = type === 'source' 
        ? "Do you want to change the source?" 
        : "Don't change this. Do you want to continue?";

    modal.style.display = 'flex';

    alertYesButton.onclick = function() {
        if (type === 'source') {
            selectElement.dataset.selected = selectElement.value;
        } else {
            selectElement.dataset.previousValue = selectElement.value;
        }
        modal.style.display = 'none';
    };

    alertCancelButton.onclick = function() {
        if (type === 'source') {
            selectElement.value = selectElement.dataset.selected;
        } else {
            selectElement.value = selectElement.dataset.previousValue;
        }
        modal.style.display = 'none';
        event.preventDefault();
    };
}

window.onclick = function(event) {
    const modal = document.getElementById('custom-alert');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
};


function deleteUpdown() {
    const updownContainer = document.getElementById('updown-sections');
    const rows = updownContainer.querySelectorAll('tbody tr');
    if (rows.length > 1) {
        rows[rows.length - 1].remove();
        disableSourceOptions();

        const newLastRow = rows[rows.length - 2]; 
        if (newLastRow) {
            const lastDestinationSelect = newLastRow.querySelector('select[name*="destination"]');
            if (lastDestinationSelect) {
                lastDestinationSelect.disabled = false;
            }
        }
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

<form action="{{ route('train.store') }}" method="POST" onsubmit="return validateUpdownSections()">
    @csrf
    <div class="card text-center" style="width: 100%; background-color: #f8f9fa; border: 1px solid #ccc;">
        <div class="card-header text-white" style="background-color: #005F56">
            Add Train
        </div>

        <div class="card-body" >
            <hr style="width: 100%; height: 2px; background-color: transparent; border: none;">
            <div class="mb-3 d-flex align-items-center">
                <label for="tname" class="form-label me-3" style="width: 250px; text-align: right;">Train Name :
                    &nbsp;</label>
                <div class="flex-grow-1">
                    <input type="text" name="tname" id="tname" class="form-control w-75" required>
                </div>
            </div>
            <hr style="width: 100%; height: 2px; background-color: black; border: none;">
            
            <div class="row">
                <div class="col-md-6 col-12" style="text-align: center; width: 100%; border-right: 1px solid #000; padding-right: 14px; padding-left: 14px;">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target=".bd-example-modal-xl-compartment" style="width:100%;">
                        Set Train Compartment
                    </button>

                    <div id="compartment-data-display" class="mt-3"></div>
                </div>

                <div class="col-md-6 col-12" style="text-align: center; width: 100%; border-left: 1px solid #000; padding-right: 14px; padding-left: 14px;">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target=".bd-example-modal-xl" style="width: 100%;">
                        Set Train Route
                    </button>

                    <div id="updown-data-display" class="mt-3"></div>
                </div>
            </div>

            <div class="modal fade bd-example-modal-xl-compartment" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg fullscreen-modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Train Compartment</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <hr style="width: 100%; height: 0px; background-color: transparent; border: none;">
                        <div class="mb-3 d-flex align-items-center">
                            <label for="numofcompartment" class="form-label me-3" style="width: 250px; text-align: right;">Number of
                                Compartments : &nbsp; </label>
                            <div class="flex-grow-1">
                                <input type="number" name="numofcompartment" id="numofcompartment" class="form-control w-75" min="1" value="1"
                                    onchange="generateCompartments()" required>
                            </div>
                        </div>

                        <div id="compartment-sections"></div>
                    </div>
                </div>
            </div>
            <hr style="width: 100%; height: 2px; background-color: black; border: none;">
            
            <div class="card" style="background-color: transparent; border: none;" >
                <div class="card-body" style="background-color: transparent; border: none;">
                <h5 class="card-title">Shown Train Routes</h5>
                    <div id="route-display" style="background-color: transparent; border: none;">
                    <div class="route-box" id="route-box-1"></div>
                    <div class="route-box" id="route-box-2"></div>
                    <div class="route-box" id="route-box-3"></div>
                    <div class="route-box" id="route-box-4"></div>
                    <div class="route-box" id="route-box-5"></div>
                    <div class="route-box" id="route-box-6"></div>
                    <div class="route-box" id="route-box-7"></div>
                    <div class="route-box" id="route-box-8"></div>
                    <div class="route-box" id="route-box-9"></div>
                    <div class="route-box" id="route-box-10"></div>
                    <div class="route-box" id="route-box-11"></div>
                    <div class="route-box" id="route-box-12"></div>
                    <div class="route-box" id="route-box-13"></div>
                    <div class="route-box" id="route-box-14"></div>
                    <div class="route-box" id="route-box-15"></div>
                </div>
                </div>
            </div>

            <hr style="width: 100%; height: 0px; background-color: transparent; border: none;">

            <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg fullscreen-modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Train Route</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div id="updown-sections"></div>
                        <input type="hidden" name="updownnumber" id="updownnumber" value="1">

                        <div class="mb-3 d-flex align-items-center justify-content-center">
                            <button type="button" class="btn btn-success" onclick="generateUpdowns()">Add</button>
                            <button type="button" class="btn btn-danger" onclick="deleteUpdown()">Delete</button>
                        </div>

                        <div id="custom-alert" class="custom-alert">
                            <div class="alert-content">
                                <p id="alert-message"></p>
                                <button id="alert-yes" class="alert-btn">Yes</button>
                                <button id="alert-cancel" class="alert-btn">Cancel</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <hr style="width: 100%; height: 2px; background-color: black; border: none;">
            <div id="warning-message" style="display:none; color: red; font-weight: bold; padding: 10px; background-color: #f8d7da; border: 1px solid #f5c6cb; margin-top: 20px;">
                <!-- Warning message will be inserted here -->
            </div>

            <button type="submit" class="btn search-btn">Submit</button>
            <hr style="width: 100%; height: 0px; background-color: transparent; border: none;">
        </div>
    </div>
</form>

<script>
function validateUpdownSections() {
    const updownContainer = document.getElementById('updown-sections');
    const rows = updownContainer.querySelectorAll('tbody tr');

    let warningMessage = '';

    if (rows.length < 2) {
        warningMessage += 'Please add at least 2 updown sections before submitting.\n';
    } else {
        const firstSource = rows[0].querySelector('select[name*="source"]').value;
        const lastDestination = rows[rows.length - 1].querySelector('select[name*="destination"]').value;

        if (firstSource !== lastDestination) {
            warningMessage += "Train route is not set! The last updown section's destination must match the first updown section's source.\n";
        }

    }
   if (warningMessage) {
        document.getElementById('warning-message').innerText = warningMessage;
        document.getElementById('warning-message').style.display = 'block';
        return false;
    } else {
        document.getElementById('warning-message').style.display = 'none';
    }

    return true;
}

function updateRouteDisplay() {
    const routeDisplay = document.getElementById('route-display');
    routeDisplay.innerHTML = ''; 

    let routeStations = [];
    let updownSections = document.querySelectorAll("#updown-sections tbody tr");

    updownSections.forEach((row, index) => {
        let source = row.querySelector(`select[name="updowns[${index + 1}][source]"]`).value;
        let destination = row.querySelector(`select[name="updowns[${index + 1}][destination]"]`).value;

        if (source) routeStations.push(source);
        if (destination) routeStations.push(destination);
    });

    let finalRoute = [];
    routeStations.forEach((station, i) => {
        if (i === 0 || station !== routeStations[i - 1]) {
            finalRoute.push(station);
        }
    });

    for (let i = 0; i < 15; i++) {
        if (i < finalRoute.length) {
            let box = `<div class="route-box">${finalRoute[i]}</div>`;
            routeDisplay.innerHTML += box;
            if (i < finalRoute.length - 1) {
                routeDisplay.innerHTML += `<div class="route-arrow">➔</div>`;
            }
        }
    }
}

document.addEventListener('change', function (event) {
    if (event.target.matches("#updown-sections select")) {
        updateRouteDisplay();
    }
});

const routeDisplay = document.getElementById('route-display');
let isDown = false;
let startX;
let scrollLeft;

routeDisplay.addEventListener('mousedown', (e) => {
    isDown = true;
    routeDisplay.classList.add('active');
    startX = e.pageX - routeDisplay.offsetLeft;
    scrollLeft = routeDisplay.scrollLeft;
});

routeDisplay.addEventListener('mouseleave', () => {
    isDown = false;
    routeDisplay.classList.remove('active');
});

routeDisplay.addEventListener('mouseup', () => {
    isDown = false;
    routeDisplay.classList.remove('active');
});

routeDisplay.addEventListener('mousemove', (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - routeDisplay.offsetLeft;
    const walk = (x - startX) * 2;
    routeDisplay.scrollLeft = scrollLeft - walk;
});
</script>
<style>
                .fullscreen-modal {
                    max-width: 1400px;
                    width: 100%;
                    height: 100%;
                    margin: 0;
                }

                .modal-dialog {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 100vh;
                    margin: 0 auto;
                }

                .modal-content {
                    height: 100%;
                    margin: auto;
                    padding: 20px;
                    border: none;
                }

                .modal-header .close {
                    font-size: 2rem;
                    color: #000;
                }

                #route-display {
                display: flex;
                gap: 10px;
                overflow-x: auto;
                white-space: nowrap;
                padding: 10px;
                cursor: grab;
                scroll-behavior: smooth;
                border: 2px solid #ccc;
                width: 100%;
                max-width: 800px;
                position: relative;
                align-items: center;
            }

            .route-box {
                width: 160px;
                height: 40px;
                border: 2px solid #005F56;
                background-color: #f8f9fa;
                text-align: center;
                line-height: 35px;
                font-weight: bold;
                font-size: 12px;
                border-radius: 5px;
                user-select: none;
                display: inline-flex;
                justify-content: center;
                align-items: center;
            }

            .route-arrow {
                font-size: 20px;
                font-weight: bold;
                color: #005F56;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .custom-alert {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                justify-content: center;
                align-items: center;
                z-index: 1000;
            }

            .alert-content {
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                text-align: center;
                width: 300px;
            }

            .alert-btn {
                padding: 10px 20px;
                margin: 10px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            #alert-yes {
                background-color: #4CAF50;
                color: white;
            }

            #alert-cancel {
                background-color: #f44336;
                color: white;
            }
            </style>
@endsection