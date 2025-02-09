@extends('layout.admin')

@section('title', 'Select Train to Edit')

@section('content')  
    <div class="card text-center" style="width: 100%; background-color: #f8f9fa; border: 1px solid #ccc;">
        <div class="card-header  text-white" style="background-color: #005F56">
                <h2>Select a Train to Edit</h2>
        </div>
        <div class="card-body">
            <div class="mb-3 d-flex align-items-center">
            @foreach ($trains as $train)
                <li>
                    <a href="{{ route('train.edit', $train->trainid) }}">{{ $train->trainname }}</a>
                </li>
             @endforeach    
            </div>
        </div>
    </div>
@endsection
