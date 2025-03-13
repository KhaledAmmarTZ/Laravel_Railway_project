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
                seatnumber: compartmentDiv.querySelector(`[name="compartments[${index}][seatnumber]"]`).value
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

    let updownCount = 0; // Initialize a counter for updown sections

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

    document.getElementById('updownnumber').value = updownCount; // Update the hidden field
}


function removeUpdown(index) {
    const updowns = document.querySelectorAll('#updown-sections .updown-item');
    if (updowns.length > 1) {
        updowns[index].remove();
        updownCount--;
        document.getElementById('updownnumber').value = updownCount; // Update the hidden field
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const stations = @json($stations->toArray()); // Station list from the database
    const existingUpdowns = @json($train->trainupdowns);
    generateCompartments(@json($train->traincompartments));
    existingUpdowns.forEach((updown) => {
        const updownRow = generateUpdownRow(updown, updownCount);
        document.getElementById('updown-sections').appendChild(updownRow);
        updownCount++;
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

<div class="row justify-content-center">
    <div class="col-md-12">
        <form action="{{ route('train.update', $train->trainid) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card text-center">
                <div class="card-header text-white" style="background-color: #005F56">
                    Edit Train
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Train Name:</label>
                        <input type="text" name="trainname" class="form-control" value="{{ $train->trainname }}" required>
                    </div>

                    <hr style="width: 100%; height: 2px; background-color: black; border: none;">

                    <div class="mb-3">
                        <label class="form-label">Number of Compartments:</label>
                        <input type="number" name="compartmentnumber" id="numofcompartment" class="form-control" onchange="generateCompartments()" value="{{ count($train->traincompartments) }}">
                    </div>
                    <div id="compartment-sections"></div>

                    <hr style="width: 100%; height: 2px; background-color: black; border: none;">
                    <input type="hidden" name="updownnumber" id="updownnumber" value="{{ count($train->trainupdowns) }}">

                    <div class="mb-3">
                        <label class="form-label">Schedules:</label>
                        <button type="button" class="btn btn-primary" onclick="addUpdown()">Add Schedule</button>
                    </div>
                    <div id="updown-sections"></div>

                    <hr style="width: 100%; height: 2px; background-color: black; border: none;">

                    <button type="submit" class="btn btn-success">Update Train</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    console.log("Train Updowns:", @json($train->trainupdowns));
</script>

@endsection
