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
            let compartment = storedData[i] || existingCompartments[i] || { id: '', compartmentname: '', seatnumber: '' };

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

    function generateUpdowns(existingUpdowns = []) {
        const numUpdown = parseInt(document.getElementById('updownnumber').value) || 0;
        const updownContainer = document.getElementById('updown-sections');

        let storedData = [];
        document.querySelectorAll('#updown-sections .updown-item').forEach((updownDiv, index) => {
            storedData.push({
                id: updownDiv.querySelector(`[name="updowns[${index}][id]"]`).value,
                tarrtime: updownDiv.querySelector(`[name="updowns[${index}][tarrtime]"]`).value,
                tdeptime: updownDiv.querySelector(`[name="updowns[${index}][tdeptime]"]`).value,
                tarrdate: updownDiv.querySelector(`[name="updowns[${index}][tarrdate]"]`).value,
                tdepdate: updownDiv.querySelector(`[name="updowns[${index}][tdepdate]"]`).value,
                tsource: updownDiv.querySelector(`[name="updowns[${index}][tsource]"]`).value,
                tdestination: updownDiv.querySelector(`[name="updowns[${index}][tdestination]"]`).value
            });
        });

        updownContainer.innerHTML = '';

        const table = document.createElement('table');
        table.classList.add('table', 'table-bordered');
        const headerRow = document.createElement('tr');
        headerRow.innerHTML = `
            <th>Source</th>
            <th>Destination</th>
            <th>Arrival Time</th>
            <th>Departure Time</th>
            <th>Arrival Date</th>
            <th>Departure Date</th> 
            <th>Actions</th>
        `;
        table.appendChild(headerRow);

        for (let i = 0; i < numUpdown; i++) {
            let updown = storedData[i] || existingUpdowns[i] || { id: '', tarrtime: '', tdeptime: '', tarrdate: '', tdepdate: '' ,tsource: '', tdestination: '' };

            const updownRow = document.createElement('tr');
            updownRow.classList.add('updown-item');
            updownRow.dataset.index = i;

            updownRow.innerHTML = `
                <td><input type="text" name="updowns[${i}][tsource]" class="form-control" value="${updown.tsource}" required></td>
                <td><input type="text" name="updowns[${i}][tdestination]" class="form-control" value="${updown.tdestination}" required></td>
                <input type="hidden" name="updowns[${i}][id]" value="${updown.id}">
                <td><input type="time" name="updowns[${i}][tarrtime]" class="form-control" value="${updown.tarrtime}" required></td>
                <td><input type="time" name="updowns[${i}][tdeptime]" class="form-control" value="${updown.tdeptime}" required></td>
                <td><input type="date" name="updowns[${i}][tarrdate]" class="form-control" value="${updown.tarrdate}" required></td>
                <td><input type="date" name="updowns[${i}][tdepdate]" class="form-control" value="${updown.tdepdate}" required></td>
                
                <td><button type="button" class="btn btn-danger" onclick="removeUpdown(${i})">Delete</button></td>
            `;

            table.appendChild(updownRow);
        }

        updownContainer.appendChild(table);
    }

    function removeCompartment(index) {
        let compartments = document.querySelectorAll('#compartment-sections .compartment-item');
        if (compartments.length > 1) {
            compartments[index].remove();
        }
    }

    function removeUpdown(index) {
        let updowns = document.querySelectorAll('#updown-sections .updown-item');
        if (updowns.length > 1) {
            updowns[index].remove();
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        generateCompartments(@json($train->traincompartments));
        generateUpdowns(@json($train->trainupdowns));
    });
</script>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
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
                            <label class="form-label" st yle="text-size: bold">Number of Compartments:</label>
                            <input type="number" name="compartmentnumber" id="numofcompartment" class="form-control" onchange="generateCompartments()" value="{{ count($train->traincompartments) }}">
                        </div>
                        <div id="compartment-sections"></div>

                        <hr style="width: 100%; height: 2px; background-color: black; border: none;">

                        <div class="mb-3">
                            <label class="form-label">Number of Schedules:</label>
                            <input type="number" name="updownnumber" id="updownnumber" class="form-control" onchange="generateUpdowns()" value="{{ count($train->trainupdowns) }}">
                        </div>
                        <div id="updown-sections"></div>

                        <hr style="width: 100%; height: 2px; background-color: black; border: none;">

                        <button type="submit" class="btn btn-success">Update Train</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
