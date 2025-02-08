<!doctype html>
<html lang="en">
  <head>
    <title>@yield('title')</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">



</head>
  <body style="background-color: #F0FFFC;">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light bg-transparent" style="height: 120px; width: 100%;">
            <a class="navbar-brand d-flex align-items-center" style="margin-left: 20px;" href="#">
                <img src="{{ asset('images/Trainlogo.png') }}" alt="Logo" style="height: 50px; width: auto; margin-right: 10px;">
                <span class="font-weight-bold">Bangladesh Railway</span>
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Centered Navbar Content -->
                <div class="mx-auto d-flex align-items-center">
                    <!-- Search Bar -->
                  
                        <input class="search-bar mx-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="search-btn" type="submit">Search</button>
                   
                    
                    <!-- Navbar Buttons -->
                    <ul class="navbar-nav d-flex align-items-center">
                        <li class="nav-item active">
                            <a class="btn custom-btn mx-2" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn custom-btn mx-2" href="#">Link</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="btn custom-btn dropdown-toggle mx-2" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Dropdown
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Right-Aligned Buttons -->
                <div class="ml-auto d-flex align-items-center">
                    <a href="#" class="btn login-btn mx-2">Login</a>
                    <a href="#" class="btn register-btn mx-2">Register</a>
                </div>
            </div>
        </nav>

        <div>
            @yield('content')
        </div>
    </div>




    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  </body>
</html>