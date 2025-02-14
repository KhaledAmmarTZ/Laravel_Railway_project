@extends('layout.master')
@section('title')
Index Page
@endsection

@section('content')
    <div style="background-image: url('{{ asset('images/Group.png') }}'); 
                background-size: cover; /* Ensures the image covers full width and height */
                background-position: center;
                width: 100%; /* Full width of the screen */
                height: 77vh; /* Full height of the viewport */
                display: flex;
                justify-content: center;
                align-items: center; 
                text-align: center; 
                color: white;">
        <div>
            <h1>Welcome to Bangladesh Railway</h1>
            <p>Explore the best train services, book your tickets, and enjoy a smooth journey across Bangladesh.</p>
            <a href="#" class="btn btn-primary">Get Started</a>
        </div>
    </div>
@endsection
