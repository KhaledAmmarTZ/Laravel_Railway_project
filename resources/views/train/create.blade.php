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
        border: 3px solid black; /* Increase border thickness */
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
        border: 3px solid black; /* Increased border thickness */
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
    const numCompartment = document.getElementById('numofcompartment').value;
    const compartmentContainer = document.getElementById('compartment-sections');

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
document.addEventListener("DOMContentLoaded", function() {
    generateUpdowns();
});

function generateUpdowns() {
    const updownContainer = document.getElementById('updown-sections');

    let existingValues = {};
    document.querySelectorAll("#updown-sections input, #updown-sections select").forEach(input => {
        existingValues[input.name] = input.value;
    });

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

    tbody.insertAdjacentHTML('beforeend', rowHTML);

    // Disable previous destination selects
    disablePreviousDestinations();

    document.querySelectorAll("#updown-sections input, #updown-sections select").forEach(input => {
        const name = input.name;
        if (existingValues[name]) {
            input.value = existingValues[name];
        }
    });

    disableSourceOptions();
    updateRouteDisplay(); // Update route display whenever new updown is added

}

function disablePreviousDestinations() {
    const updowns = document.querySelectorAll("#updown-sections table tbody tr");

    updowns.forEach((row, index) => {
        if (index < updowns.length - 1) { // Apply only to all except the last row
            const destinationSelect = row.querySelector('select[name*="destination"]');
            
            destinationSelect.addEventListener('change', function(event) {
                const userResponse = confirm("Don't change this. Do you want to continue?");
                if (!userResponse) {
                    event.preventDefault();  // Prevent the change
                    this.value = this.dataset.previousValue || ""; // Reset to the previous value
                } else {
                    this.dataset.previousValue = this.value; // Store the new value
                }
            });

            // Store the initial value to reset if needed
            destinationSelect.dataset.previousValue = destinationSelect.value;
        }
    });
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

function updateDepTime(selectElement, index) {
    const selectedOption = selectElement.selectedOptions[0];
    let depTime = selectedOption.getAttribute('data-deptime');

    if (depTime) {
        depTime = depTime.substring(0, 5);
    }

    const sourceValue = selectElement.value;
    const destinationSelect = document.getElementById(`updowns[${index}][destination]`);

    // Enable all destination options
    Array.from(destinationSelect.options).forEach(option => {
        option.disabled = false;
    });

    // Disable the current source station in the destination options
    Array.from(destinationSelect.options).forEach(option => {
        if (option.value === sourceValue) {
            option.disabled = true;
        }
    });
}

function updateArrTime(selectElement, index) {
    const selectedOption = selectElement.selectedOptions[0];
    const arrTime = selectedOption.getAttribute('data-artime');

    const destinationValue = selectElement.value;
    const sourceSelect = document.getElementById(`updowns[${index}][source]`);

    // Enable all source options
    Array.from(sourceSelect.options).forEach(option => {
        option.disabled = false;
    });

    // Disable the current destination station in the source options
    Array.from(sourceSelect.options).forEach(option => {
        if (option.value === destinationValue) {
            option.disabled = true;
        }
    });
    // Disable selected destinations in all dropdowns
    destinationSelects.forEach(select => {
        Array.from(select.options).forEach(option => {
            if (option.value === destinationValue) {
                option.disabled = true;
            }
        });

        // Ensure the selected value remains enabled in its own dropdown
        if (select === selectElement) {
            select.querySelector(`option[value="${destinationValue}"]`).disabled = false;
        }
    });

}

function deleteUpdown() {
    const updownContainer = document.getElementById('updown-sections');
    const rows = updownContainer.querySelectorAll('tbody tr');
    if (rows.length > 1) {
        rows[rows.length - 1].remove();
        disableSourceOptions();

        // Re-enable the destination field of the new last row
        const newLastRow = rows[rows.length - 2]; // Now the last row
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
    <div class="card text-center" style="width: 1200px; background-color: #f8f9fa; border: 1px solid #ccc;">
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
            
            <div class="row" style="justify-content: center; gap: 30px">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target=".bd-example-modal-xl-compartment">Set Train Compartment</button>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target=".bd-example-modal-xl">Set Train Route</button>
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
                                <input type="number" name="numofcompartment" id="numofcompartment" class="form-control w-75" min="1"
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

                    </div>
                </div>
            </div>

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
                    min-height: 100vh; /* Center vertically */
                    margin: 0 auto; /* Center horizontally */
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
            </style>

            <hr style="width: 100%; height: 2px; background-color: black; border: none;">
            <button type="submit" class="btn search-btn">Submit</button>
            <hr style="width: 100%; height: 0px; background-color: transparent; border: none;">
        </div>
    </div>
</form>

<script>
function validateUpdownSections() {
    const updownContainer = document.getElementById('updown-sections');
    const rows = updownContainer.querySelectorAll('tbody tr');

    if (rows.length < 2) {
        alert("Please add at least 2 updown sections before submitting.");
        return false;
    }

    const firstSource = rows[0].querySelector('select[name*="source"]').value;
    const lastDestination = rows[rows.length - 1].querySelector('select[name*="destination"]').value;

    if (firstSource !== lastDestination) {
        alert(
            "Train route is note set!.The last updown section's destination must match the first updown section's source."
        );
        return false;
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
</script>
<script>
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
@endsection