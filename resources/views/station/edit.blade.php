@extends('layout.admin')

@section('content')
<div class="container">
    <h2>Edit Station</h2>

    <form action="{{ route('station.update', $station->stid) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Station Name:</label>
            <input type="text" name="stationname" class="form-control" value="{{ $station->stationname }}" required>
        </div>
        <div class="form-group">
            <label>City:</label>
            <input type="text" name="city" class="form-control" value="{{ 
            $station->city }}" required>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection