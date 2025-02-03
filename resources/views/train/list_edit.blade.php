@extends('layout.admin')

@section('title', 'Select Train to Edit')

@section('content')  
    @foreach ($trains as $train)
    <div class="card text-center" style="width: 100%; background-color: #f8f9fa; border: 1px solid #ccc;">
        <div class="card-header  text-white" style="background-color: #005F56">
                <h2>Select a Train to Edit</h2>
        </div>
        <div class="card-body">
            <div class="mb-3 d-flex align-items-center">
                <li>
                    <a href="{{ route('train.edit', $train->tid) }}">{{ $train->tname }}</a>
                </li>
            </div>
        </div>
    </div>
    @endforeach
@endsection
