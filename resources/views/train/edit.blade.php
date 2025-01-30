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
</head>
<body>

@if (isset($train))
<h2>Edit Train</h2>

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('train.update', $train->tid) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Train Name</label>
    <input type="text" name="tname" value="{{ $train->tname }}" required>

    <label>Number of Compartments</label>
    <input type="number" name="numofcompartment" id="numofcompartment" value="{{ $train->numofcompartment }}" required 
           onchange="generateCompartments(this.value)">

    <div id="compartment-sections"></div>

    <label>Departure Time</label>
    <input type="time" name="deptime" value="{{ $train->deptime->deptime ?? '' }}" required>

    <label>Arrival Time</label>
    <input type="time" name="arrtime" value="{{ $train->arrtime->arrtime ?? '' }}" required>


    <label>Source</label>
    <input type="text" name="source" value="{{ $train->source->source ?? '' }}" required>

    <label>Destination</label>
    <input type="text" name="destination" value="{{ $train->destination->destination ?? '' }}" required>

    <button type="submit">Update Train</button>
</form>


<form action="{{ route('train.destroy', $train->tid) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit" onclick="return confirm('Are you sure you want to delete this train?')">Delete Train</button>
</form>
@endif

@endsection
