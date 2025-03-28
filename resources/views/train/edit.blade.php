@extends('layout.admin')

@section('title', 'Edit Train')

@section('content')

<script>
function generateCompartments(existingCompartments = []) {
    const numCompartment = parseInt(document.getElementById('numofcompartment').value) || 0;
    const compartmentContainer = document.getElementById('compartment-sections');

    // Add hidden field for compartment number
    const compartmentNumberField = document.getElementById('compartmentnumber');
    if (compartmentNumberField) {
        compartmentNumberField.value = numCompartment;
    }

    let storedData = [];
    document.querySelectorAll('#compartment-sections .compartment-item').forEach((compartmentDiv, index) => {
        storedData.push({
            id: compartmentDiv.querySelector(`[name="compartments[${index}][id]"]`).value,
            compartmentname: compartmentDiv.querySelector(`[name="compartments[${index}][compartmentname]"]`).value,
            total_seats: compartmentDiv.querySelector(`[name="compartments[${index}][total_seats]"]`).value,
            compartmenttype: compartmentDiv.querySelector(`[name="compartments[${index}][compartmenttype]"]`).value,
            price: compartmentDiv.querySelector(`[name="compartments[${index}][price]"]`).value
        });
    });

    compartmentContainer.innerHTML = '';

    const table = document.createElement('table');
    table.classList.add('table', 'table-bordered');
    const headerRow = document.createElement('tr');
    headerRow.innerHTML = `
        <th>Compartment Name</th>
        <th>Number of Seats</th>
        <th>Type</th>
        <th>Price</th>
        <th>Actions</th>
    `;
    table.appendChild(headerRow);

    for (let i = 0; i < numCompartment; i++) {
        let compartment = storedData[i] || existingCompartments[i] || { id: '', compartmentname: '', total_seats: '', compartmenttype: '', price: '' };

        const compartmentRow = document.createElement('tr');
        compartmentRow.classList.add('compartment-item');
        compartmentRow.dataset.index = i;

        compartmentRow.innerHTML = `
            <input type="hidden" name="compartments[${i}][id]" value="${compartment.id}">
            <td><input type="text" name="compartments[${i}][compartmentname]" class="form-control" value="${compartment.compartmentname}" required></td>
            <td><input type="number" name="compartments[${i}][total_seats]" class="form-control" value="${compartment.total_seats}" required></td>
            <td><input type="text" name="compartments[${i}][compartmenttype]" class="form-control" value="${compartment.compartmenttype}" required></td>
            <td><input type="number" name="compartments[${i}][price]" class="form-control" value="${compartment.price}" required></td>
            <td><button type="button" class="btn btn-danger" onclick="removeCompartment(${i})">Delete</button></td>
        `;

        table.appendChild(compartmentRow);
    }

    compartmentContainer.appendChild(table);
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
        <h3 style="font-weight:bold; color:white">Compartment Data:</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="font-weight:bold; color:white">Compartment Name</th>
                    <th style="font-weight:bold; color:white">Number of Seats</th>
                    <th style="font-weight:bold; color:white">Compartment Type</th>
                    <th style="font-weight:bold; color:white">Price</th>
                </tr>
            </thead>
            <tbody>
    `;

    document.querySelectorAll('#compartment-sections .compartment-item').forEach((compartmentRow) => {
        const name = compartmentRow.querySelector(`[name*="[compartmentname]"]`)?.value || '';
        const seats = compartmentRow.querySelector(`[name*="[total_seats]"]`)?.value || '';
        const type = compartmentRow.querySelector(`[name*="[compartmenttype]"]`)?.value || '';
        const price = compartmentRow.querySelector(`[name*="[price]"]`)?.value || '';

        if (name || seats || type || price) {
            displayHTML += `
                <tr>
                    <td style="font-weight:bold; color:white">${name}</td>
                    <td style="font-weight:bold; color:white">${seats}</td>
                    <td style="font-weight:bold; color:white">${type}</td>
                    <td style="font-weight:bold; color:white">${price}</td>
                </tr>
            `;
        }
    });

    displayHTML += `</tbody></table>`;
    document.getElementById('compartment-data-display').innerHTML = displayHTML;
}



    let updownCount = 0; 

    function generateUpdownRow(updown = { id: '', tarrtime: '', tdeptime: '', tarrdate: '', tdepdate: '', tsource: '', tdestination: '' }, index) {
    const stations = @json($stations->toArray()); 

    const updownRow = document.createElement('tr');
    updownRow.classList.add('updown-item');
    updownRow.dataset.index = index;


        // Add hidden field for updown number
        const updownNumberField = document.getElementById('updownnumber');
    if (updownNumberField) {
        updownNumberField.value = index + 1; // Number of updowns
    }

    const rowStyle = new Date(`${updown.tdepdate}T${updown.tdeptime}`) > new Date() ? '' : 'background-color: #ffcccc;';

    updownRow.innerHTML = `
        <input type="hidden" name="updowns[${index}][id]" value="${updown.id}">
        <td style="${rowStyle}">
            <select name="updowns[${index}][tsource]" class="form-control updown-input" required>
                <option value="">Select Source</option>
                ${stations.map(station => `<option value="${station.stationname}" ${station.stationname === updown.tsource ? 'selected' : ''}>${station.stationname}</option>`).join('')}
            </select>
        </td>
        <td style="${rowStyle}">
            <select name="updowns[${index}][tdestination]" class="form-control updown-input" required>
                <option value="">Select Destination</option>
                ${stations.map(station => `<option value="${station.stationname}" ${station.stationname === updown.tdestination ? 'selected' : ''}>${station.stationname}</option>`).join('')}
            </select>
        </td>
        <td style="${rowStyle}"><input type="time" name="updowns[${index}][tarrtime]" class="form-control updown-input" value="${updown.tarrtime}" required></td>
        <td style="${rowStyle}"><input type="time" name="updowns[${index}][tdeptime]" class="form-control updown-input" value="${updown.tdeptime}" required></td>
        <td style="${rowStyle}"><input type="date" name="updowns[${index}][tarrdate]" class="form-control updown-input" value="${updown.tarrdate}" required></td>
        <td style="${rowStyle}"><input type="date" name="updowns[${index}][tdepdate]" class="form-control updown-input" value="${updown.tdepdate}" required></td>
        <td><button type="button" class="btn btn-danger" onclick="removeUpdown(${index})">Delete</button></td>
    `;

    const displayDiv = document.getElementById('updown-data-display');
    let table = document.getElementById('updown-data-table');

    if (!table) {
        table = document.createElement('table');
        table.id = 'updown-data-table';
        table.classList.add('table', 'table-bordered', 'mt-2');
        table.innerHTML = `
            <thead>
                <tr>
                    <th style="font-weight:bold; color:white">Source</th>
                    <th style="font-weight:bold; color:white">Destination</th>
                    <th style="font-weight:bold; color:white">Arrival Time</th>
                    <th style="font-weight:bold; color:white">Departure Time</th>
                    <th style="font-weight:bold; color:white">Arrival Date</th>
                    <th style="font-weight:bold; color:white">Departure Date</th>
                </tr>
            </thead>
            <tbody id="updown-data-body"></tbody>
        `;
        displayDiv.appendChild(table);
    }

    const tbody = document.getElementById('updown-data-body');
    const updownDataRow = document.createElement('tr');
    
    updownDataRow.innerHTML = `
        <td style="font-weight:bold; color:white">${updown.tsource || 'N/A'}</td>
        <td style="font-weight:bold; color:white">${updown.tdestination || 'N/A'}</td>
        <td style="font-weight:bold; color:white">${updown.tarrtime || 'N/A'}</td>
        <td style="font-weight:bold; color:white">${updown.tdeptime || 'N/A'}</td>
        <td style="font-weight:bold; color:white">${updown.tarrdate || 'N/A'}</td>
        <td style="font-weight:bold; color:white">${updown.tdepdate || 'N/A'}</td>
    `;

    tbody.appendChild(updownDataRow);

    return updownRow;
}


function addUpdown() {
    const updownContainer = document.getElementById('updown-sections');
    const newUpdownRow = generateUpdownRow({}, updownCount);
    updownContainer.appendChild(newUpdownRow);
    updownCount++;

    document.getElementById('updownnumber').value = updownCount; 
}

function removeUpdown(index) {
    const updowns = document.querySelectorAll('#updown-sections .updown-item');
    if (updowns.length > 1) {
        updowns[index].remove();
        updownCount--;
        document.getElementById('updownnumber').value = updownCount; 
    }
}

function removeCompartment(index) {
    const compartments = document.querySelectorAll('#compartment-sections .compartment-item');
    if (compartments.length > 1) {
        compartments[index].remove();
    }
}
document.addEventListener("DOMContentLoaded", function () {
    const stations = @json($stations->toArray());
    const existingUpdowns = @json($train->trainupdowns);
    generateCompartments(@json($train->traincompartments));
    existingUpdowns.forEach((updown) => {
        const updownRow = generateUpdownRow(updown, updownCount);
        document.getElementById('updown-sections').appendChild(updownRow);
        updownCount++;
    });
    const updowns = @json($train->trainupdowns);
    const routeDisplay = document.getElementById("route-display");
    routeDisplay.innerHTML = ""; 

    let previousDestination = null;
    let boxIndex = 1;

    updowns.forEach((updown, index) => {
        if (index === 0 || updown.tsource !== previousDestination) {
            const sourceBox = document.createElement("div");
            sourceBox.className = "route-box";
            sourceBox.textContent = updown.tsource;
            routeDisplay.appendChild(sourceBox);
        }

        const destinationBox = document.createElement("div");
        destinationBox.className = "route-box";
        destinationBox.textContent = updown.tdestination;
        routeDisplay.appendChild(destinationBox);

        previousDestination = updown.tdestination;
    });
});
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

<form action="{{ route('train.update', $train->trainid) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card text-center" style="width: 100%; min-width: 320px; background: rgba(255, 255, 255, 0.3); border: 1px solid #ccc;">
        <div class="card-header text-white" style="background-color: #005F56">
            {{ $train->trainname }} Train
        </div>
        <div class="row">
            <div class="col-md-9 col-12">
                <div class="d-flex flex-column">
                    <div class="mb-3 d-flex align-items-center">
                        <label class="form-label me-3" style="width: 350px; text-align: right; font-weight:bold; color:white">
                            Train Name: {{ $train->trainname }} 
                        </label>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <!-- Train Image Label -->
                        <label for="train_image" class="form-label me-3" style="width: 250px; text-align: right; font-weight: bold; color: white;">
                            Train Image : &nbsp;
                        </label>
                        @if ($train->train_image)
                            <div style="flex-shrink: 0;">
                                <img src="{{ asset('storage/' . $train->train_image) }}" alt="Train Image" class="img-fluid ms-2" id="train_image_preview" style="max-width: 500px; height: auto;">
                            </div>
                        @endif
                    </div>
                </div>
                <hr style="width: 100%; height: 0px; background-color: transparent; border: none;">
                <h3 style="font-weight:bold; color:white">Train Route:</h3>
                <div id="updown-data-display" class="mt-3"></div>
                <div id="compartment-data-display" class="mt-3"></div>
                <hr style="width: 100%; height: 0px; background-color: transparent; border: none;">
                <div class="card-body" style="background-color: transparent; border: none;">
                    <div class="mb-3 d-flex align-items-center">
                        <h5 class="card-title" style="font-weight:bold; color:white ;width: 250px; text-align: right;">Shown Train Routes :</h5>
                        <div id="route-display" style="background-color: transparent; border: none;">
                            @for ($i = 1; $i <= 15; $i++)
                                <div class="route-box" id="route-box-{{ $i }}"></div>
                            @endfor
                        </div>
                    </div>
                </div>        
            </div>
                
            <div class="col-md-3 col-12">
                <hr style="width: 100%; height: 0px; background-color: transparent; border: none;">
                <!-- Button to trigger modal -->
                <button type="button" class="btn btn-success btn-block d-flex justify-content-center align-items-center" data-toggle="modal" data-target="#trainModal" style="height: 50px;">
                    Image and Name
                </button>

                <!-- Modal -->
                <div class="modal fade" id="trainModal" tabindex="-1" role="dialog" aria-labelledby="trainModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="trainModalLabel">Train Image and Name</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('train.update', $train->trainid) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <!-- Train Name -->
                                    <div class="mb-3 d-flex align-items-center">
                                        <label class="form-label me-3" style="width: 250px; text-align: right;">Train Name:  </label>
                                        <div class="flex-grow-1">
                                            <input type="text" name="trainname" class="form-control" value="{{ $train->trainname }}" required>
                                        </div>
                                    </div>
                   
                                    <!-- Train Image -->
                                    <div class="mb-3 d-flex align-items-center">
                                        <label class="form-label me-3" style="width: 250px; text-align: right;">Train Image: </label>
                                        <div class="flex-grow-1">
                                            <input type="file" name="train_image" class="form-control" id="train_image_input" onchange="previewImage(event)">
                                            
                                            <!-- Placeholder for the new image preview -->
                                            <div class="mt-2" id="new_image_preview_container" style="display: none;">
                                                <img id="new_image_preview" class="img-fluid" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card" style="background-color: transparent; border: none;">
                    <hr style="width: 100%; height: 0px; background-color: transparent; border: none;">

                    <button type="button" class="btn btn-success btn-block d-flex justify-content-center align-items-center" data-toggle="modal" data-target=".bd-example-modal-xl" style="height: 50px;">
                        Set Train Route
                    </button>

                    <hr style="width: 100%; height: 0px; background-color: transparent; border: none;">

                    <button type="button" class="btn btn-success btn-block d-flex justify-content-center align-items-center" data-toggle="modal" data-target=".bd-example-modal-xl-compartment" style="height: 50px;">
                        Set Train Compartment
                    </button>
                    <a href="{{ route('train.pdf', $train->trainid) }}" class="btn btn-danger">Download PDF</a>


                    <div class="modal fade bd-example-modal-xl-compartment" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg fullscreen-modal">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Train Compartment</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="mb-3 d-flex align-items-center">
                                    <label class="form-label me-3" style="width: 250px; text-align: right;">Number of Compartments:</label>
                                    <input type="number" name="compartmentnumber" id="numofcompartment" class="form-control" onchange="generateCompartments()" value="{{ count($train->traincompartments) }}">
                                </div>
                                <div id="compartment-sections"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg fullscreen-modal">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Train Route</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <input type="hidden" name="updownnumber" id="updownnumber" value="{{ count($train->trainupdowns) }}">
                                                        
                                <div id="updown-sections">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="width: 265px;">Source</th>
                                                <th style="width: 265px;">Destination</th>
                                                <th style="width: 150px;">Arrival Time</th>
                                                <th style="width: 180px;">Departure Time</th>
                                                <th style="width: 180px;">Arrival Date</th>
                                                <th>Departure Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <hr style="width: 100%; height: 0px; background-color: transparent; border: none;">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-success" onclick="addUpdown()">Add Schedule</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr style="width: 100%; height: 2px; background-color: black; border: none;">                    

                    <button type="submit" class="btn search-btn btn-block">Update Train</button>
                    <hr style="width: 100%; height: 0px; background-color: transparent; border: none;">
                </div>
            </div>
        </div>
    </div>
</form>
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
                    .updown-item td {
                        border: 2px solid #005F56;
                        padding: 12px;
                    }

                    .updown-item {
                        border: 2px solid #005F56;
                    }

                    th {
                        border: 2px solid #005F56;
                        padding: 10px;
                        font-weight: bold;
                    }

                    th {
                        background-color:rgb(157, 157, 157);
                    }
            </style>
<script>
    console.log("Train Updowns:", @json($train->trainupdowns));
</script>
<script>
            function previewImage(event) {
                const file = event.target.files[0];
                const reader = new FileReader();

                reader.onload = function(e) {
                    const imagePreview = document.getElementById('new_image_preview');
                    const previewContainer = document.getElementById('new_image_preview_container');
                    
                    // Show the preview of the chosen image
                    imagePreview.src = e.target.result;
                    previewContainer.style.display = 'block';
                };

                if (file) {
                    reader.readAsDataURL(file); // Read the selected image file
                }
            }
        </script>
@endsection
