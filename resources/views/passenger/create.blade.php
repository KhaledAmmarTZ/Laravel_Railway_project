@extends('layouts.app')
@section('title')
    Passenger Page
@endsection

@section('content')
<div class="my-5"></div>
  @session('msg')
    <div class="alert alert-success">
        {{session('msg')}}
    </div> 
  @endsession
  <div class="container">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('passenger.store')}}" method="post">
                        @csrf
                        <div class="mb-5">
                            <label for="trainid">Train Name: </label>
                            <p> {{$train->trainname}}</p>
                            @error('trainid')
                                <span class="text-danger">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                        <div class="mb-5">
                            <label for="ticket_count">How many tickets do you want: </label>
                            <input type="number" name="ticket_count" id="ticket_count" class="form-control" value="1" min="1" max="4" required>
                        </div>
                        <div class="mb-5">
                            <label for="mealop">Do you want to add meal with your ticket: </label>
                            <input type="checkbox" name="mealop" id="" checked>
                        </div>
                        <div class="mb-5">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


