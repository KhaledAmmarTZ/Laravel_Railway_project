@extends('layout.admin')

@section('content')
<div class="container">
    <h2>Add Station</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('station.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Station Name:</label>
            <input type="text" name="stationname" class="form-control" required>
        </div>
        <div class="form-group">
            <label>City:</label>
            <input type="text" name="city" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
@endsection