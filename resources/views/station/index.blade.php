@extends('layout.admin')

@section('content')
<div class="container">
    <h2>Station List</h2>
    <a href="{{ route('station.create') }}" class="btn btn-primary mb-3">Add Station</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <!--th>ID</th-->
                <th>Station Name</th>
                <th>City</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stations as $station)
            <tr>
                <!--td>{{ $station->stid }}</td-->
                <td>{{ $station->stationname }}</td>
                <td>{{ $station->city }}</td>
                <td>
                    <a href="{{ route('station.show', $station->stid) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('station.edit', $station->stid) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('station.destroy', $station->stid) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection