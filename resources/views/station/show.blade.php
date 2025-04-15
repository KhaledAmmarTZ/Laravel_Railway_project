@extends('layout.admin')

@section('content')
<div class="card text-center" style="width: 100%; border: 2px solid #ccc; background: rgba(255, 255, 255, 0.3);">
    <div class="card-header text-black" style="background-color: rgb(255, 255, 255); font-weight: bold; border-radius: 1px; padding: 1px;">
        Station Details
    </div>

    <div class="card-body">

        <hr style="width: 100%; height: 2px; background-color: transparent; border: none;">

        <div class="mb-3 d-flex align-items-center">
            <label class="form-label me-3" style="width: 350px; text-align: right; font-weight: bold; color: white;">
                Station Name: &nbsp;
            </label>
            <div>
                <span style="font-weight: bold; color: white;">{{ $station->stationname }}</span>
            </div>
        </div>

        <div class="mb-3 d-flex align-items-center">
            <label class="form-label me-3" style="width: 350px; text-align: right; font-weight: bold; color: white;">
                City: &nbsp;
            </label>
            <div >
                <span style="font-weight: bold; color: white;">{{ $station->city }}</span>
            </div>
        </div>

        <hr style="width: 100%; height: 2px; background-color: rgba(255, 255, 255, 0.5); border: none;">

        <a href="{{ route('station.index') }}" class="btn btn-secondary">Back</a>

        <hr style="width: 100%; height: 0px; background-color: transparent; border: none;">
    </div>
</div>
@endsection
