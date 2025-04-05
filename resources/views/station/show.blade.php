@extends('layout.admin')

@section('content')
<div class="container">
    <h2>Station Details</h2>
    <p><strong>Station Name:</strong> {{ $station->stationname }}</p>
    <p><strong>City:</strong> {{ $station->city }}</p>

    <a href="{{ route('station.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection