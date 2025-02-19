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
        <div style="display: flex; width: 90%;  justify-content: space-between;">
            <!-- Left Side (Text Section) -->
            <div style="flex: 1; 
            text-align: left; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            align-items: flex-start; 
            padding-left: 50px; /* Adjust spacing from left */">

                <h1 style="font-size: 40px; 
                        font-weight: bold; 
                        color: #081F5C; 
                        margin-bottom: 15px;">
                    Welcome to Bangladesh Railway
                </h1>

                <p style="font-size: 20px; 
                        color: #081F5C; 
                        max-width: 500px; /* Limits text width for better readability */">
                    Explore the best train services, book your tickets, and enjoy a smooth journey across Bangladesh.
                </p>

            </div>
            <!-- Right Side (Image Section) -->
            <div style="flex: 1; text-align: right; display: flex; justify-content: center; align-items: center;">
            <img src="{{ asset('images/train(5).jpg') }}" alt="Train Image" 
                style="width: 90%; /* Adjust the size */
                        max-width: 1000px; /* Optional: Set a maximum width */
                        height: auto; 
                        object-fit: cover; /* Maintain aspect ratio */
                        border-radius: 10px; /* Optional: Adds rounded corners */
                        margin-left: 235px; /* Moves the image to the left */
                        ">
            </div>
        </div>
    </div>
    <div class="bd-example" style="display: flex; justify-content: center; align-items: center; height: 100vh; border-radius: 10px;">
        <div id="carouselExampleCaptions" class="carousel slide carousel-fade" data-ride="carousel" style="width: 98.3%; height: 100%; border-radius: 15px; border: 4px solid #ccc;">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
                <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner" style="height: 100%;">
                <div class="carousel-item active" data-interval="2000" style="height: 100%; border-radius: 10px;">
                    <img src="{{ asset('images/train (3).jpg') }}" class="d-block w-100" alt="Train Image" style="height: 100%; object-fit: cover; border-radius: 10px;">
                    <div class="carousel-caption d-none d-md-block" style="background: rgba(0, 0, 0, 0.5); padding: 10px; border-radius: 10px;">
                        <h5>First slide label</h5>
                        <p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
                    </div>
                </div>
                <div class="carousel-item" data-interval="2000" style="height: 100%;">
                    <img src="{{ asset('images/train (6).jpg') }}" class="d-block w-100" alt="Train Logo" style="height: 100%; object-fit: contain; border-radius: 10px;">
                    <div class="carousel-caption d-none d-md-block" style="background: rgba(0, 0, 0, 0.5); padding: 10px; border-radius: 10px;">
                        <h5>Second slide label</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                </div>
                <div class="carousel-item" data-interval="2000" style="height: 100%;">
                    <img src="{{ asset('images/train (1).jpg') }}" class="d-block w-100" alt="Train Image" style="height: 100%; object-fit: cover; border-radius: 10px;">
                    <div class="carousel-caption d-none d-md-block" style="background: rgba(0, 0, 0, 0.5); padding: 10px; border-radius: 10px;">
                        <h5>Third slide label</h5>
                        <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.</p>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <div style="height: 160px;"></div> <!-- Spacer div -->
    <div class="card-deck" style="width: 99.7%; margin: 0 auto; border-radius: 10px; background-color: #F0FFFC; ">
        <div class="card custom-card" style="border-radius: 6px; background-color: transparent;">
            <div class="card-body"  style="background-color: #081F5C; border-radius: 5px 5px 0 0;">
                <img src="{{ asset('images/train (1).jpg') }}" class="card-img" alt="Train" style="display: block; margin: 0 auto; width: 80%; border-radius: 5px;">
                <h5 class="card-title text-center" style="color: #F0FFFC;">Train</h5>
                <p class="card-text" style="color: #F0FFFC;">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
            </div>
            <div class="card-footer" style="border-radius: 0 0 10px 10px;">
                <small class="text-muted">Last updated 3 mins ago</small>
            </div>
        </div>
        <div class="card custom-card" style="border-radius: 6px; background-color: transparent;">
            <div class="card-body"  style="background-color: #081F5C; border-radius: 5px 5px 0 0;">
                <img src="{{ asset('images/train (1).jpg') }}" class="card-img" alt="Train" style="display: block; margin: 0 auto; width: 80%; border-radius: 5px;">
                <h5 class="card-title text-center" style="color: #F0FFFC;">Train</h5>
                <p class="card-text" style="color: #F0FFFC;">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
            </div>
            <div class="card-footer" style="border-radius: 0 0 10px 10px;">
                <small class="text-muted">Last updated 3 mins ago</small>
            </div>
        </div>
        <div class="card custom-card" style="border-radius: 6px; background-color: transparent;">
            <div class="card-body"  style="background-color: #081F5C; border-radius: 5px 5px 0 0;">
                <img src="{{ asset('images/train (1).jpg') }}" class="card-img" alt="Train" style="display: block; margin: 0 auto; width: 80%; border-radius: 5px;">
                <h5 class="card-title text-center" style="color: #F0FFFC;">Train</h5>
                <p class="card-text" style="color: #F0FFFC;">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
            </div>
            <div class="card-footer" style="border-radius: 0 0 10px 10px;">
                <small class="text-muted">Last updated 3 mins ago</small>
            </div>
        </div>
        <div class="card custom-card" style="border-radius: 6px; background-color: transparent;">
            <div class="card-body"  style="background-color: #081F5C; border-radius: 5px 5px 0 0;">
                <img src="{{ asset('images/train (1).jpg') }}" class="card-img" alt="Train" style="display: block; margin: 0 auto; width: 80%; border-radius: 5px;">
                <h5 class="card-title text-center" style="color: #F0FFFC;">Train</h5>
                <p class="card-text" style="color: #F0FFFC;">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
            </div>
            <div class="card-footer" style="border-radius: 0 0 10px 10px;">
                <small class="text-muted">Last updated 3 mins ago</small>
            </div>
        </div>
    </div>
    <div style="height: 160px;"></div> <!-- Spacer div -->
@endsection
