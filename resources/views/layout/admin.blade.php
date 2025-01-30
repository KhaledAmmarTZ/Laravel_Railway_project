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
                </div>
            </div>
        </nav>
        <div class="container-fluid">
            <div class="row flex-xl-nowrap">
                <!-- Sidebar with a Vertical Line -->
                <div class="col-12 col-md-3 col-xl-2 bd-sidebar p-3 border-right border-secondary" style="min-height: 100vh;">
                    <form class="bd-search d-flex align-items-center mb-3">
                        <!-- Profile Image -->
                        <img src="profile.jpg" alt="Profile Picture" class="rounded-circle border" width="40" height="40">
                        
                        <!-- Profile Name -->
                        <span class="ml-2 font-weight-bold">John Doe</span>
                    </form>
                    <hr class="my-3">
                    <nav class="bd-links" id="bd-docs-nav">
                        <div class="d-grid gap-2">
                            <a href="#" class="btn btn-primary btn-block">Profile</a>
                            <button class="btn btn-success btn-block" type="button" data-toggle="collapse" data-target="#trainOptions" aria-expanded="false">
                                Train
                            </button>
                            <div class="collapse" id="trainOptions">
                                <a href="#" class="btn btn-info btn-block mt-2">Edit Train</a>
                            </div>
                        </div>
                    </nav>
                </div>

                <!-- Main Content Area -->
                <div class="col p-3">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>