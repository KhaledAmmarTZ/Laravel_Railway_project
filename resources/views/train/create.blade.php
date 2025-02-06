@extends('layout.admin')
@section('title')
    Add Train
@endsection
@section('content')
    <script>
        // Function to dynamically create compartments based on selected number
        function generateCompartments() {
            const numCompartment = document.getElementById('numofcompartment').value;
            const compartmentContainer = document.getElementById('compartment-sections');
            compartmentContainer.innerHTML = '';

            if (numCompartment > 0) {
                for (let i = 1; i <= numCompartment; i++) {
                    const compartmentDiv = document.createElement('div');
                    compartmentDiv.classList.add('mb-3');
                    compartmentDiv.innerHTML = `
                        <div class="d-flex align-items-center">
                            <label for="compartments[${i}][name]" class="form-label me-4" style="width: 350px; text-align: right;">
                                Compartment ${i} Name:
                            </label>
                            <input type="text" name="compartments[${i}][name]" id="compartments[${i}][name]" class="form-control flex-grow-1" required>
                        </div>

                        <div class="d-flex align-items-center mt-2">
                            <label for="compartments[${i}][seats]" class="form-label me-4" style="width: 350px; text-align: right;">
                                Compartment ${i} Number of Seats:
                            </label>
                            <input type="number" name="compartments[${i}][seats]" id="compartments[${i}][seats]" class="form-control flex-grow-1" required>
                        </div>
                    `;
                    compartmentContainer.appendChild(compartmentDiv);
                }
            }
        }

        // Function to dynamically create updown sections based on selected number
        function generateUpdowns() {
            const numUpdown = document.getElementById('updownnumber').value;
            const updownContainer = document.getElementById('updown-sections');
            updownContainer.innerHTML = '';

            if (numUpdown > 0) {
                for (let i = 1; i <= numUpdown; i++) {
                    const updownDiv = document.createElement('div');
                    updownDiv.classList.add('mb-3');
                    updownDiv.innerHTML = `
                        <div class="d-flex align-items-center">
                            <label for="updowns[${i}][source]" class="form-label me-4" style="width: 350px; text-align: right;">
                                Updown ${i} Source:
                            </label>
                            <input type="text" name="updowns[${i}][source]" id="updowns[${i}][source]" class="form-control flex-grow-1" required>
                        </div>

                        <div class="d-flex align-items-center mt-2">
                            <label for="updowns[${i}][destination]" class="form-label me-4" style="width: 350px; text-align: right;">
                                Updown ${i} Destination:
                            </label>
                            <input type="text" name="updowns[${i}][destination]" id="updowns[${i}][destination]" class="form-control flex-grow-1" required>
                        </div>

                        <div class="d-flex align-items-center mt-2">
                            <label for="updowns[${i}][deptime]" class="form-label me-4" style="width: 350px; text-align: right;">
                                Updown ${i} Departure Time:
                            </label>
                            <input type="time" name="updowns[${i}][deptime]" id="updowns[${i}][deptime]" class="form-control flex-grow-1" required>
                        </div>

                        <div class="d-flex align-items-center mt-2">
                            <label for="updowns[${i}][arrtime]" class="form-label me-4" style="width: 350px; text-align: right;">
                                Updown ${i} Arrival Time:
                            </label>
                            <input type="time" name="updowns[${i}][arrtime]" id="updowns[${i}][arrtime]" class="form-control flex-grow-1" required>
                        </div>
                    `;
                    updownContainer.appendChild(updownDiv);
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
                                <label for="updownnumber" class="form-label me-3" style="width: 150px; text-align: right;">Number of Updowns :</label>
                                <input type="number" name="updownnumber" id="updownnumber" class="form-control flex-grow-1" min="1" onchange="generateUpdowns()" required>
                            </div>

                            <div id="updown-sections"></div> <!-- Dynamic updown sections will appear here -->

                            <button type="submit" class="btn search-btn">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
