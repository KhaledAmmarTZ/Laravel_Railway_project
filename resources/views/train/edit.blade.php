@extends('layout.admin')

@section('title', 'Edit Train')

@section('content')

<script>
    function generateCompartments(existingCompartments = []) {
        const numCompartment = parseInt(document.getElementById('numofcompartment').value) || 0;
        const compartmentContainer = document.getElementById('compartment-sections');

        let storedData = [];
        document.querySelectorAll('#compartment-sections .compartment-item').forEach((compartmentDiv, index) => {
            storedData.push({
                id: compartmentDiv.querySelector(`[name="compartments[${index}][id]"]`).value,
                compartmentname: compartmentDiv.querySelector(`[name="compartments[${index}][compartmentname]"]`).value,
                seatnumber: compartmentDiv.querySelector(`[name="compartments[${index}][seatnumber]"]`).value,
                compartmenttype: compartmentDiv.querySelector(`[name="compartments[${index}][compartmenttype]"]`).value
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
            <th>Actions</th>
        `;
        table.appendChild(headerRow);

        for (let i = 0; i < numCompartment; i++) {
            let compartment = storedData[i] || existingCompartments[i] || { id: '', compartmentname: '', seatnumber: '', compartmenttype: '' };

            const compartmentRow = document.createElement('tr');
            compartmentRow.classList.add('compartment-item');
            compartmentRow.dataset.index = i;

            compartmentRow.innerHTML = `
                <input type="hidden" name="compartments[${i}][id]" value="${compartment.id}">
                <td><input type="text" name="compartments[${i}][compartmentname]" class="form-control" value="${compartment.compartmentname}" required></td>
                <td><input type="number" name="compartments[${i}][seatnumber]" class="form-control" value="${compartment.seatnumber}" required></td>
                <td><input type="text" name="compartments[${i}][compartmenttype]" class="form-control" value="${compartment.compartmenttype}" required></td>
                <td><button type="button" class="btn btn-danger" onclick="removeCompartment(${i})">Delete</button></td>
            `;

            table.appendChild(compartmentRow);
        }

        compartmentContainer.appendChild(table);
    }

    let updownCount = 0; 

    function generateUpdownRow(updown = { id: '', tarrtime: '', tdeptime: '', tarrdate: '', tdepdate: '', tsource: '', tdestination: '' }, index) {
    const stations = @json($stations->toArray()); 

    const updownRow = document.createElement('tr');
    updownRow.classList.add('updown-item');
    updownRow.dataset.index = index;

    const rowStyle = new Date(`${updown.tdepdate}T${updown.tdeptime}`) > new Date() ? '' : 'background-color: #ffcccc;';

    updownRow.innerHTML = `
        <input type="hidden" name="updowns[${index}][id]" value="${updown.id}">
        <td style="${rowStyle}">
            <select id="tsource-${index}" name="updowns[${index}][tsource]" class="form-control" required>
                <option value="">Select Source</option>
                ${stations.map(station => `<option value="${station.stationname}" ${station.stationname === updown.tsource ? 'selected' : ''}>${station.stationname}</option>`).join('')}
            </select>
        </td>
        <td style="${rowStyle}">
            <select id="tdestination-${index}" name="updowns[${index}][tdestination]" class="form-control" required>
                <option value="">Select Destination</option>
                ${stations.map(station => `<option value="${station.stationname}" ${station.stationname === updown.tdestination ? 'selected' : ''}>${station.stationname}</option>`).join('')}
            </select>
        </td>
        <td style="${rowStyle}"><input type="time" name="updowns[${index}][tarrtime]" class="form-control" value="${updown.tarrtime}" required></td>
        <td style="${rowStyle}"><input type="time" name="updowns[${index}][tdeptime]" class="form-control" value="${updown.tdeptime}" required></td>
        <td style="${rowStyle}"><input type="date" name="updowns[${index}][tarrdate]" class="form-control" value="${updown.tarrdate}" required></td>
        <td style="${rowStyle}"><input type="date" name="updowns[${index}][tdepdate]" class="form-control" value="${updown.tdepdate}" required></td>
        <td><button type="button" class="btn btn-danger" onclick="removeUpdown(${index})">Delete</button></td>
    `;

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


        <form action="{{ route('train.update', $train->trainid) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card text-center" style="width: 1200px; background-color: #f8f9fa; border: 1px solid #ccc;">
                
                <div class="card-header text-white" style="background-color: #005F56">
                    Edit Train
                </div>
                <div class="card-body">
                <hr style="width: 100%; height: 0px; background-color: transparent; border: none;">
                    <div class="mb-3 d-flex align-items-center">
                        <label class="form-label me-3" style="width: 250px; text-align: right;">Train Name:  </label>
                        <div class="flex-grow-1">
                        <input type="text" name="trainname" class="form-control w-75" value="{{ $train->trainname }}" required>
                        </div>
                    </div>

                    <hr style="width: 100%; height: 2px; background-color: black; border: none;">

                    <div class="mb-3 d-flex align-items-center">
                        <label class="form-label me-3" style="width: 250px; text-align: right;">Number of Compartments:</label>
                        <input type="number" name="compartmentnumber" id="numofcompartment" class="form-control w-75" onchange="generateCompartments()" value="{{ count($train->traincompartments) }}">
                    </div>
                    <div id="compartment-sections"></div>

                    <hr style="width: 100%; height: 2px; background-color: black; border: none;">
            
                    <div class="card" style="background-color: transparent; border: none;" >
                        <div class="card-body" style="background-color: transparent; border: none;">
                        <div class="mb-3 d-flex align-items-center">
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

                </div>
                

<hr style="width: 100%; height: 0px; background-color: transparent; border: none;">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target=".bd-example-modal-xl">Set Train Route</button>
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

                    <button type="submit" class="btn search-btn">Update Train</button>
                    <hr style="width: 100%; height: 0px; background-color: transparent; border: none;">
                </div>
            </div>
        </form>
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
@endsection
