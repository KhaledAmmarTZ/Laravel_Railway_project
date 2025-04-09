@extends('layout.admin')

@section('content')
<div class="card text-center" style="width: 100%; border: 2px solid #ccc; background: rgba(255, 255, 255, 0.3);">
    <div class="card-header text-black" style="background-color: rgb(255, 255, 255); font-weight: bold; border-radius: 1px; padding: 1px;">
        Edit Station
    </div>

    <div class="card-body">
        <form action="{{ route('station.update', $station->stid) }}" method="POST">
            @csrf
            @method('PUT')

            <hr style="width: 100%; height: 2px; background-color: transparent; border: none;">

            <div class="mb-3 d-flex align-items-center">
                <label for="stationname" class="form-label me-3" style="width: 350px; text-align: right; font-weight: bold; color: white;">
                    Station Name: &nbsp;
                </label>
                <div class="flex-grow-1">
                    <input type="text" name="stationname" id="stationname" class="form-control w-75"
                        value="{{ $station->stationname }}" required>
                </div>
            </div>

            <div class="mb-3 d-flex align-items-center">
                <label for="city" class="form-label me-3" style="width: 350px; text-align: right; font-weight: bold; color: white;">
                    City: &nbsp;
                </label>
                <div class="flex-grow-1">
                    <input type="text" name="city" id="city" class="form-control w-75"
                        value="{{ $station->city }}" required>
                </div>
            </div>

            <hr style="width: 100%; height: 2px; background-color: rgba(255, 255, 255, 0.5); border: none;">

            <button type="submit" class="btn search-btn">Update</button>

            <hr style="width: 100%; height: 0px; background-color: transparent; border: none;">
        </form>
    </div>
</div>
@endsection
