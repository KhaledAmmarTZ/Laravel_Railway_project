@extends('layout.master')

@section('title', 'Select Train to Edit')

@section('content')
    <h2>Select a Train to Edit</h2>
    <ul>
        @foreach ($trains as $train)
            <li>
                <a href="{{ route('train.edit', $train->tid) }}">{{ $train->tname }}</a>
            </li>
        @endforeach
    </ul>
@endsection
