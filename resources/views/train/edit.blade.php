@extends('layout.admin')

@section('title', 'Edit Train')

@section('content')

<script>
    function generateCompartments(existingCompartments = []) {
        const numCompartment = parseInt(document.getElementById('numofcompartment').value) || 0;
        const compartmentContainer = document.getElementById('compartment-sections');

        // Store existing values before clearing
        let storedData = [];
        document.querySelectorAll('#compartment-sections .compartment-item').forEach((compartmentDiv, index) => {
            storedData[index] = {
                id: compartmentDiv.querySelector(`[name="compartments[${index}][id]"]`).value,
                compartmentname: compartmentDiv.querySelector(`[name="compartments[${index}][compartmentname]"]`).value,
                seatnumber: compartmentDiv.querySelector(`[name="compartments[${index}][seatnumber]"]`).value
            };
        });

        compartmentContainer.innerHTML = ''; // Clear container

        for (let i = 0; i < numCompartment; i++) {
            let compartment = storedData[i] || existingCompartments[i] || { id: '', compartmentname: '', seatnumber: '' };

            const compartmentDiv = document.createElement('div');
            compartmentDiv.classList.add('mb-3', 'compartment-item');

            compartmentDiv.innerHTML = `
                <input type="hidden" name="compartments[${i}][id]" value="${compartment.id}">
                <div class="d-flex align-items-center">
                    <label class="form-label me-4" style="width: 350px; text-align: right;">
                        Compartment ${i + 1} Name:
                    </label>
                    <input type="text" name="compartments[${i}][compartmentname]" class="form-control flex-grow-1" value="${compartment.compartmentname}" required>
                </div>
                <div class="d-flex align-items-center mt-2">
                    <label class="form-label me-4" style="width: 350px; text-align: right;">
                        Number of Seats:
                    </label>
                    <input type="number" name="compartments[${i}][seatnumber]" class="form-control flex-grow-1" value="${compartment.seatnumber}" required>
                </div>
            `;

            compartmentContainer.appendChild(compartmentDiv);
        }
    }

    function generateUpdowns(existingUpdowns = []) {
        const numUpdown = parseInt(document.getElementById('updownnumber').value) || 0;
        const updownContainer = document.getElementById('updown-sections');

        // Store existing values before clearing
        let storedData = [];
        document.querySelectorAll('#updown-sections .updown-item').forEach((updownDiv, index) => {
            storedData[index] = {
                id: updownDiv.querySelector(`[name="updowns[${index}][id]"]`).value,
                tarrtime: updownDiv.querySelector(`[name="updowns[${index}][tarrtime]"]`).value,
                tdeptime: updownDiv.querySelector(`[name="updowns[${index}][tdeptime]"]`).value,
                tsource: updownDiv.querySelector(`[name="updowns[${index}][tsource]"]`).value,
                tdestination: updownDiv.querySelector(`[name="updowns[${index}][tdestination]"]`).value
            };
        });

        updownContainer.innerHTML = ''; // Clear container

        for (let i = 0; i < numUpdown; i++) {
            let updown = storedData[i] || existingUpdowns[i] || { id: '', tarrtime: '', tdeptime: '', tsource: '', tdestination: '' };

            const updownDiv = document.createElement('div');
            updownDiv.classList.add('mb-3', 'updown-item');

            updownDiv.innerHTML = `
                <input type="hidden" name="updowns[${i}][id]" value="${updown.id}">
                <div class="d-flex align-items-center">
                    <label class="form-label me-4" style="width: 350px; text-align: right;">
                        Arrival Time${i + 1}:
                    </label>
                    <input type="time" name="updowns[${i}][tarrtime]" class="form-control flex-grow-1" value="${updown.tarrtime}" required>
                </div>
                <div class="d-flex align-items-center mt-2">
                    <label class="form-label me-4" style="width: 350px; text-align: right;">
                        Departure Time${i + 1}:
                    </label>
                    <input type="time" name="updowns[${i}][tdeptime]" class="form-control flex-grow-1" value="${updown.tdeptime}" required>
                </div>
                <div class="d-flex align-items-center mt-2">
                    <label class="form-label me-4" style="width: 350px; text-align: right;">
                        Source${i + 1}:
                    </label>
                    <input type="text" name="updowns[${i}][tsource]" class="form-control flex-grow-1" value="${updown.tsource}" required>
                </div>
                <div class="d-flex align-items-center mt-2">
                    <label class="form-label me-4" style="width: 350px; text-align: right;">
                        Destination${i + 1}:
                    </label>
                    <input type="text" name="updowns[${i}][tdestination]" class="form-control flex-grow-1" value="${updown.tdestination}" required>
                </div>
            `;

            updownContainer.appendChild(updownDiv);
        }
    }
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
                            <input type="number" name="compartmentnumber" id="numofcompartment" class="form-control" 
                                value="{{ count($train->traincompartments) }}" 
                                onchange="generateCompartments()">
                        </div>

                        <div id="compartment-sections"></div>

                        <div class="mb-3">
                            <label class="form-label">Number of Updowns:</label>
                            <input type="number" name="updownnumber" id="updownnumber" class="form-control" 
                                value="{{ count($train->trainupdowns) }}" 
                                onchange="generateUpdowns()">

                        </div>

                        <div id="updown-sections"></div>

                        <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to update this train?')">Update Train</button>
                    </div>
                </div>
            </form>
            <form action="{{ route('train.destroy', $train->trainid) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn delete-btn mx-2" onclick="return confirm('Are you sure you want to delete this train?')">Delete Train</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        generateCompartments(@json($train->traincompartments));
        generateUpdowns(@json($train->trainupdowns));
    });
</script>


@endsection
