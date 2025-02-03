@extends('layout.admin')

@section('title', 'Train to Edit')

@section('content')
    <script>
    function generateCompartments(num) {
        const compartmentContainer = document.getElementById('compartment-sections');
        compartmentContainer.innerHTML = '';

        let compartments = @json($train->compartments ?? []);

        for (let i = 0; i < num; i++) {
            let name = compartments[i] ? compartments[i].nameofeachcompartment : '';
            let seats = compartments[i] ? compartments[i].numofseat : '';

            const compartmentDiv = document.createElement('div');
            compartmentDiv.classList.add('compartment');

            compartmentDiv.innerHTML = `
                <label>Compartment ${i + 1} Name</label>
                <input type="text" name="compartments[${i}][name]" value="${name}" required>
                
                <label>Number of Seats</label>
                <input type="number" name="compartments[${i}][seats]" value="${seats}" required>
                <br>
            `;

            compartmentContainer.appendChild(compartmentDiv);
        }
    }

    // Call this function on page load if train data exists
    window.onload = function () {
        let num = document.getElementById('numofcompartment').value;
        if (num) {
            generateCompartments(num);
        }
    };
</script>

@if (isset($train))
@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<div class="container mt-4">
    <div class="row">
        <div class="col-12"> <!-- Full width column -->
            <form action="{{ route('train.update', $train->tid) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card text-center" style="width: 100%; background-color: #f8f9fa; border: 1px solid #ccc;">
                    <div class="card-header text-white" style="background-color: #005F56">
                        Edit Train
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="tname" class="form-label">Train Name</label>
                            <input type="text" id="tname" name="tname" value="{{ $train->tname }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="numofcompartment" class="form-label">Number of Compartments</label>
                            <input type="number" id="numofcompartment" name="numofcompartment" value="{{ $train->numofcompartment }}" class="form-control" required onchange="generateCompartments(this.value)">
                        </div>

                        <div id="compartment-sections"></div>

                        <div class="mb-3">
                            <label for="deptime" class="form-label">Departure Time</label>
                            <input type="time" id="deptime" name="deptime" value="{{ $train->deptime->deptime ?? '' }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="arrtime" class="form-label">Arrival Time</label>
                            <input type="time" id="arrtime" name="arrtime" value="{{ $train->arrtime->arrtime ?? '' }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="source" class="form-label">Source</label>
                            <input type="text" id="source" name="source" value="{{ $train->source->source ?? '' }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="destination" class="form-label">Destination</label>
                            <input type="text" id="destination" name="destination" value="{{ $train->destination->destination ?? '' }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent border-0 text-center">
                        <button type="submit" class="btn update-btn mx-2" onclick="return confirm('Are you sure you want to update this train?')">Update Train</button>
                        
                        <!-- Delete Form -->
                        <form action="{{ route('train.destroy', $train->tid) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn delete-btn mx-2" onclick="return confirm('Are you sure you want to delete this train?')">Delete Train</button>
                        </form>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>





@endif

@endsection
