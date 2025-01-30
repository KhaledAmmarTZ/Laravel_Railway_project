@extends('layout.admin')

@section('title', 'Select Train to Edit')

@section('content')
    <h2>Select a Train to Edit</h2>
    <ul>
        @foreach ($trains as $train)
        <div class="d-flex justify-content-center align-items-start shadow p-3 mb-5 bg-white rounded" style="height: 500vh; padding-top: 250px;">
            <div class="card text-center" style="width: 50%; background-color: #f8f9fa; border: 1px solid #ccc;">
                <div class="card-header bg-primary text-white">
                    Add Train
                </div>
                <div class="card-body">
                    <div class="mb-3 d-flex align-items-center">
                        <li>
                            <a href="{{ route('train.edit', $train->tid) }}">{{ $train->tname }}</a>
                        </li>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </ul>
@endsection
