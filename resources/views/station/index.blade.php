@extends('layout.admin')

@section('content')
<div class="card text-center" style="width: 100%; border: 2px solid #ccc; background: rgba(255, 255, 255, 0.3);">
    <div class="card-header text-black" style="background-color: rgb(255, 255, 255); font-weight: bold; border-radius: 1px; padding: 1px;">
        Station List
    </div>

    <div class="card-body">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('station.create') }}" class="btn search-btn">Add Station</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success text-start">{{ session('success') }}</div>
            <hr style="width: 100%; height: 2px; background-color: rgba(255, 255, 255, 0.5); border: none;">
        @endif

        <div class="table-responsive">
            <table class="table table-bordered text-white" style="background-color: rgba(0, 0, 0, 0.2);">
                <thead class="text-white" style="background-color: rgba(255, 255, 255, 0.2);">
                    <tr>
                        <th>Station Name</th>
                        <th>City</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stations as $station)
                        <tr>
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
    </div>
</div>
@endsection
