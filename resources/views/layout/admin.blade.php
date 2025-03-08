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
    <link href="https://fonts.googleapis.com/css2?family=Sansation&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sansation:wght@900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

</head>
  <body style="background-color: #F0FFFC;">
    <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light" style=" margin: 10px 17px; border-radius: 5px; background-color: #005F56; font-family: 'Sansation', sans-serif; font-weight: 700;">
                <a class="navbar-brand text-white" href="#">
                <img src="{{ asset('images/Trainlogo.png') }}" alt="Logo" style="height: 30px; width: auto; margin-right: 10px;">
                <span class="font-weight-bold">Bangladesh Railway</span>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" 
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>


                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                    </ul>
                    <form class="form-inline my-2 my-lg-0">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                    </form>

                    <!-- Profile Section -->
                    <form class="form-inline my-2 my-lg-0 mx-3">
                        <img src="{{ auth()->guard('admin')->user()->admin_image ? asset('storage/' . auth()->guard('admin')->user()->admin_image) : asset('images/default-profile.png') }}" 
                            alt="Profile Picture" class="rounded-circle border" width="25" height="25">
                        <span class="ml-2 font-weight-bold text-white">{{ auth()->guard('admin')->user()->name }}</span>
                    </form>

                    <!-- Vertical Line -->
                    <div class="mx-2" style="border-left: 1px solid white; height: 25px;"></div>

                    <!-- Profile Icon (Thin) -->
                    <a href="{{ route('admin.profiles') }}" class="mx-2 d-flex align-items-center">
                        <i class="bi bi-person" style="color: white; font-size: 22px;"></i>
                    </a>

                    <!-- Settings Icon -->
                    <a href="{{ route('admin.profile.edit') }}" class="mx-2 d-flex align-items-center">
                        <i class="bi bi-gear" style="color: white; font-size: 22px;"></i> 
                    </a>

                    <form class="form-inline my-2 my-lg-0 ml-3" action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-block" style="background: none; border: none; padding: 0;">
                            <i class="fa-solid fa-arrow-right-from-bracket" style="color: white; font-size: 20px;"></i>
                        </button>
                    </form>
                </div>
            </nav>
        <div class="container-fluid">
            <div class="row flex-xl-nowrap" style="min-height: 90vh; max-height: 91vh;">
                <!-- Sidebar with Border-Right for Vertical Line -->
                <div class="col-12 col-md-3 col-xl-2 p-3 sidebar" >
                    <div class="card  h-100" style="background-color: #F0FFFC; border: 1px solid #005F56;">
                        <!-- Sidebar Header <div class="card-header bg-primary text-white text-center">
                            Sidebar Menu
                        </div> -->
                        <div class="card-body"> 
                            <!-- Navigation Buttons -->
                            <nav class="bd-links">
                            <div class="d-grid gap-2">
                                <a href="{{ url('/admin/dashboard') }}" class="btn btn-success btn-block">Dashboard</a>
                                <a href="{{ url('/admin/profiles') }}" class="btn btn-success btn-block">Profile</a>
                                <!-- Train Section -->
                                <button class="btn btn-success btn-block mb-2" type="button" data-toggle="collapse" data-target="#trainOptions">
                                    Train
                                </button>
                                <div class="collapse mb-3 " id="trainOptions">
                                    <!-- <a href="{{ url('/admin/train/edit') }}" class="btn sidebar-btn btn-block ml-3">Edit Train</a> -->
                                    <a href="{{ url('/admin/train/create') }}" class="btn sidebar-btn btn-block ml-3">Add Train</a>
                                    <a href="{{ url('/admin/train/show') }}" class="btn sidebar-btn btn-block ml-3">View Trains</a>
                                </div>

                                <!-- Station Section -->
                                <button class="btn btn-success btn-block mb-2" type="button" data-toggle="collapse" data-target="#StationOptions">
                                    Station
                                </button>
                                <div class="collapse mb-3" id="StationOptions">
                                    <a href="{{ url('#') }}" class="btn sidebar-btn btn-block ml-3">Edit Station</a>
                                    <a href="{{ url('#') }}" class="btn sidebar-btn btn-block ml-3">Add Station</a>
                                    <a href="{{ url('#') }}" class="btn sidebar-btn btn-block ml-3">View Stations</a>
                                </div>
                                <button class="btn btn-success btn-block mb-2" type="button" data-toggle="collapse" data-target="#StuffOptions">
                                    Stuffs
                                </button>
                                <div class="collapse mb-3" id="StuffOptions">
                                    <a href="{{ url('#') }}" class="btn sidebar-btn btn-block ml-3">Edit Stuff</a>
                                    <a href="{{ url('#') }}" class="btn sidebar-btn btn-block ml-3">Add Stuff</a>
                                    <a href="{{ url('#') }}" class="btn sidebar-btn btn-block ml-3">View Stuffs</a>
                                </div>
                                <button class="btn btn-success btn-block mb-2" type="button" data-toggle="collapse" data-target="#UserOptions">
                                    User
                                </button>
                                <div class="collapse mb-3" id="UserOptions">
                                    <a href="{{ url('#') }}" class="btn sidebar-btn btn-block ml-3">Edit User</a>
                                    <a href="{{ url('#') }}" class="btn sidebar-btn btn-block ml-3">Add User</a>
                                    <a href="{{ url('#') }}" class="btn sidebar-btn btn-block ml-3">View User</a>
                                </div>
                                <button class="btn btn-success btn-block mb-2" type="button" data-toggle="collapse" data-target="#PassengerOptions">
                                    Passengers
                                </button>
                                <div class="collapse mb-3" id="PassengerOptions">
                                    <a href="{{ url('#') }}" class="btn sidebar-btn btn-block ml-3">Edit Passenger</a>
                                    <a href="{{ url('#') }}" class="btn sidebar-btn btn-block ml-3">Add Passanger</a>
                                    <a href="{{ url('#') }}" class="btn sidebar-btn btn-block ml-3">View Passanger</a>
                                </div>
                                <button class="btn btn-success btn-block mb-2" type="button" data-toggle="collapse" data-target="#TicketOptions">
                                    Ticket
                                </button>
                                <div class="collapse mb-3" id="TicketOptions">
                                    <a href="{{ url('#') }}" class="btn sidebar-btn btn-block ml-3">Edit Ticket</a>
                                    <a href="{{ url('#') }}" class="btn sidebar-btn btn-block ml-3">Add Ticket</a>
                                    <a href="{{ url('#') }}" class="btn sidebar-btn btn-block ml-3">View Ticket</a>
                                </div>
                            </div>
                        </nav>

                        </div>                     
                        <!-- <div class="card-footer border-0 text-center" style="background-color: #F0FFFC;">
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-block">Logout</button>
                            </form>
                        </div> -->
                    </div>
                </div>

                <!-- Main Content Section -->
                <div class="col p-3 main-content">
                    <div class="d-flex justify-content-center align-items-start  p-3 mb-5 rounded" style=" padding-top: 50px; background-color: #F0FFFC; border: 1px solid #005F56;">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function updateDateTime() {
            let now = new Date();
            let date = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            let time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

            document.getElementById('datetime').innerHTML = `<span style="font-size: 22px;">${time}</span> <span style="font-size: 16px; margin-top: 5px;">${date}</span>`;
        }
        setInterval(updateDateTime, 1000); // Update every second
        updateDateTime(); // Initial call
    </script>
</body>
</html>