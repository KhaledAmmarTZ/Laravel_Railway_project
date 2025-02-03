@extends('layout.admin')
@section('title')
index page
@endsection
@section('content')
    <script>
    // Function to dynamically create compartments based on selected number
    function generateCompartments() {
        const numCompartment = document.getElementById('numofcompartment').value;  // Get the number of compartments entered
        const compartmentContainer = document.getElementById('compartment-sections'); // The div where compartments will be added
        compartmentContainer.innerHTML = '';  // Clear any existing compartments

        // Ensure the entered value is a number greater than 0
        if (numCompartment > 0) {
            // Loop through and create compartments based on selected number
            for (let i = 1; i <= numCompartment; i++) {
                const compartmentDiv = document.createElement('div');
                compartmentDiv.classList.add('mb-3'); // Bootstrap margin bottom for spacing

                compartmentDiv.innerHTML = `
                    <div class="d-flex align-items-center">
                        <label for="compartments[${i}][name]" class="form-label me-4" style="width: 350px; text-align: right;">
                            Compartment ${i} Name :
                        </label>
                        <input type="text" name="compartments[${i}][name]" id="compartments[${i}][name]" class="form-control flex-grow-1" required>
                    </div>

                    <div class="d-flex align-items-center mt-2">
                        <label for="compartments[${i}][seats]" class="form-label me-4" style="width: 350px; text-align: right;">
                            Compartment ${i} Number of Seats :
                        </label>
                        <input type="number" name="compartments[${i}][seats]" id="compartments[${i}][seats]" class="form-control flex-grow-1" required>
                    </div>
                `;

                compartmentContainer.appendChild(compartmentDiv); // Append the compartment input to the container
            }
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
            <form action="{{ route('train.store') }}" method="POST">
                @csrf
                <div class="card text-center" style="width: 100%; background-color: #f8f9fa; border: 1px solid #ccc;">
                    <div class="card-header text-white" style="background-color: #005F56">
                        Add Train
                    </div>
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-center">
                            <label for="tname" class="form-label me-3" style="width: 150px; text-align: right;">Train Name :</label>
                            <input type="text" name="tname" id="tname" class="form-control flex-grow-1" required>
                        </div>

                        <div class="mb-3 d-flex align-items-center">
                            <label for="numofcompartment" class="form-label me-3" style="width: 150px; text-align: right;">Number of Compartments :</label>
                            <input type="number" name="numofcompartment" id="numofcompartment" class="form-control flex-grow-1" min="1" onchange="generateCompartments()" required>
                        </div>

                        <div id="compartment-sections"></div> <!-- Dynamic compartments will appear here -->

                        <div class="mb-3 d-flex align-items-center">
                            <label for="deptime" class="form-label me-3" style="width: 150px; text-align: right;">Departure Time :</label>
                            <input type="time" name="deptime" id="deptime" class="form-control flex-grow-1" required>
                        </div>

                        <div class="mb-3 d-flex align-items-center">
                            <label for="arrtime" class="form-label me-3" style="width: 150px; text-align: right;">Arrival Time :</label>
                            <input type="time" name="arrtime" id="arrtime" class="form-control flex-grow-1" required>
                        </div>

                        <div class="mb-3 d-flex align-items-center">
                            <label for="source" class="form-label me-3" style="width: 150px; text-align: right;">Source :</label>
                            <input type="text" name="source" id="source" class="form-control flex-grow-1" required>
                        </div>

                        <div class="mb-3 d-flex align-items-center">
                            <label for="destination" class="form-label me-3" style="width: 150px; text-align: right;">Destination :</label>
                            <input type="text" name="destination" id="destination" class="form-control flex-grow-1" required>
                        </div>

                        <button type="submit" class="btn search-btn">Submit</button> 
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

