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

        for (let i = 0; i < numCompartment; i++) {
            let compartment = storedData[i] || existingCompartments[i] || { id: '', compartmentname: '', seatnumber: '' };

            const compartmentDiv = document.createElement('div');
            compartmentDiv.classList.add('mb-3', 'compartment-item');
            compartmentDiv.dataset.index = i;
            
            compartmentDiv.innerHTML = `
                <input type="hidden" name="compartments[${i}][id]" value="${compartment.id}">
                <div class="d-flex align-items-center">
                    <label class="form-label me-4">Compartment ${i + 1} Name:</label>
                    <input type="text" name="compartments[${i}][compartmentname]" class="form-control" value="${compartment.compartmentname}" required>
                </div>
                <div class="d-flex align-items-center mt-2">
                    <label class="form-label me-4">Number of Seats:</label>
                    <input type="number" name="compartments[${i}][seatnumber]" class="form-control" value="${compartment.seatnumber}" required>
                </div>
                <div class="d-flex align-items-center mt-2">
                    <label class="form-label me-4">Type:</label>
                    <input type="text" name="compartments[${i}][compartmenttype]" class="form-control" value="${compartment.compartmenttype}" required>
                </div>
                <button type="button" class="btn btn-danger mt-2" onclick="removeCompartment(${i})">Delete</button>
            `;

            compartmentContainer.appendChild(compartmentDiv);
        }
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

        for (let i = 0; i < numUpdown; i++) {
            let updown = storedData[i] || existingUpdowns[i] || { id: '', tarrtime: '', tdeptime: '', tarrdate: '', tdepdate: '' ,tsource: '', tdestination: '' };

            const updownDiv = document.createElement('div');
            updownDiv.classList.add('mb-3', 'updown-item');
            updownDiv.dataset.index = i;
            
            updownDiv.innerHTML = `
                <input type="hidden" name="updowns[${i}][id]" value="${updown.id}">

                <div class="d-flex align-items-center">
                    <label class="form-label me-4">Arrival ${i + 1}:</label>
                    <input type="time" name="updowns[${i}][tarrtime]" 
                        class="form-control" 
                        value="${(updown.tarrtime)}" 
                        required>
                </div>

                <div class="d-flex align-items-center mt-2">
                    <label class="form-label me-4">Departure ${i + 1}:</label>
                    <input type="time" name="updowns[${i}][tdeptime]" 
                        class="form-control" 
                        value="${(updown.tdeptime)}" 
                        required>
                </div>

                <div class="d-flex align-items-center mt-2">
                    <label class="form-label me-4">Arrival Date ${i + 1}:</label>
                    <input type="date" name="updowns[${i}][tarrdate]" 
                        class="form-control" 
                        value="${updown.tarrdate}" 
                        required>
                </div>

                <div class="d-flex align-items-center mt-2">
                    <label class="form-label me-4">Departure Date ${i + 1}:</label>
                    <input type="date" name="updowns[${i}][tdepdate]" 
                        class="form-control" 
                        value="${updown.tdepdate}" 
                        required>
                </div>

                <div class="d-flex align-items-center mt-2">
                    <label class="form-label me-4">Source ${i + 1}:</label>
                    <input type="text" name="updowns[${i}][tsource]" 
                        class="form-control" 
                        value="${updown.tsource}" 
                        required>
                </div>

                <div class="d-flex align-items-center mt-2">
                    <label class="form-label me-4">Destination ${i + 1}:</label>
                    <input type="text" name="updowns[${i}][tdestination]" 
                        class="form-control" 
                        value="${updown.tdestination}" 
                        required>
                </div>

                <button type="button" class="btn btn-danger mt-2" onclick="removeUpdown(${i})">Delete</button>
            `;

            updownContainer.appendChild(updownDiv);
        }
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

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
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

                        <div class="mb-3">
                            <label class="form-label">Number of Compartments:</label>
                            <input type="number" name="compartmentnumber" id="numofcompartment" class="form-control" onchange="generateCompartments()" value="{{ count($train->traincompartments) }}">
                        </div>
                        <div id="compartment-sections"></div>

                        <div class="mb-3">
                            <label class="form-label">Number of Updowns:</label>
                            <input type="number" name="updownnumber" id="updownnumber" class="form-control" onchange="generateUpdowns()" value="{{ count($train->trainupdowns) }}">
                        </div>
                        <div id="updown-sections"></div>

                        <button type="submit" class="btn btn-success">Update Train</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
