@extends('layout.admin')

@section('title', 'Show Train')

@section('content')
<div class="card text-center" style="width: 100%; background-color: #f8f9fa; border: 1px solid #ccc;">
    <div class="card-header text-white" style="background-color: #005F56">
      Train List
      @if(session('success'))
        <div class="alert alert-success text-center w-100">
            {{ session('success') }}
        </div>
      @endif
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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trains as $train)
                    @foreach ($train->trainupdowns as $index => $updown)
                        <tr>
                            @if ($index == 0)
                                <!-- Only show train details for the first updown entry -->
                                <td rowspan="{{ count($train->trainupdowns) }}">{{ $train->trainname }}</td>
                                <td rowspan="{{ count($train->trainupdowns) }}">{{ $train->compartmentnumber }}</td>
                            @endif
                            <td>{{ $updown->tdeptime }}</td>
                            <td>{{ $updown->tarrtime }}</td>
                            <td>{{ $updown->tsource }}</td>
                            <td>{{ $updown->tdestination }}</td>

                            @if ($index == 0)
                                <!-- Show compartments only once for the first updown row -->
                                <td rowspan="{{ count($train->trainupdowns) }}">
                                    <ul>
                                        @foreach ($train->traincompartments as $compartment)
                                            <li>Name:{{ $compartment->compartmentname }} (Seats: {{ $compartment->seatnumber }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td rowspan="{{ count($train->trainupdowns) }}">
                                    <!-- Edit Button -->
                                    <a href="{{ route('train.edit', $train->trainid) }}" class="btn update-btn btn-sm">Edit</a>
                                    <!-- Delete Form -->
                                    <form action="{{ route('train.destroy', $train->trainid) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn delete-btn btn-sm" onclick="return confirm('Are you sure you want to delete this train?')">Delete</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer text-center d-flex justify-content-center align-items-center" style="background-color: #005F56">
        <div class="mx-auto d-flex align-items-center">
            <input class="search-bar mx-2" type="search" placeholder="Search" aria-label="Search">
            <button class="search-btn" type="submit">Search</button>                                                    
        </div>
    </div>
</div>
@endsection
