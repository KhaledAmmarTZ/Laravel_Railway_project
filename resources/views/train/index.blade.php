@extends('layout.master')
@section('title')
index page
@endsection

@section('content')
<div class="card text-center">
    <div class="card-header">
      Train List
    </div>
    <div class="card-body">
        <table class="table" border="2">
            <thead>
                <tr>
                    <th>Train Name</th>
                    <th>Number of Compartments</th>
                    <th>Departure Time</th>
                    <th>Arrival Time</th>
                    <th>Source</th>
                    <th>Destination</th>
                    <th>Compartments</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trains as $train)
            <tr>
                <td>{{ $train->tname }}</td>
                <td>{{ $train->numofcompartment }}</td>
                <td>{{ $train->deptime->deptime ?? 'Not Available' }}</td>
                <td>{{ $train->arrtime->arrtime ?? 'Not Available' }}</td>
                <td>{{ $train->source->source ?? 'Not Available' }}</td>
                <td>{{ $train->destination->destination ?? 'Not Available' }}</td>
                <td>
                            <ul>
                                @foreach ($train->compartments as $compartment)
                                    <li>{{ $compartment->nameofeachcompartment }} (Seats: {{ $compartment->numofseat }})</li>
                                @endforeach
                            </ul>
                        </td>
            </tr>
            @endforeach

            </tbody>
        </table>
    </div>
    <div class="card-footer text-center d-flex justify-content-center align-items-center">
        <ul class="nav nav-pills card-header-pills">
            <li class="nav-item">
                <a class="nav-link active" href="#">Active</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#">Disabled</a>
            </li>
        </ul>
    </div>
</div>
@endsection
    


