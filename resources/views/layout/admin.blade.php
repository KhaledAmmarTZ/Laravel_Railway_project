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





</head>
  <body style="background-color: #F0FFFC;">
    <div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-light" style="height: 100px; margin: 10px 17px; border-radius: 5px; background-color: #005F56; font-family: 'Sansation', sans-serif; font-weight: 700;">
    <a class="navbar-brand d-flex align-items-center text-white" style="margin-left: 20px; font-weight: bold;" href="#">
        <img src="{{ asset('images/Trainlogo.png') }}" alt="Logo" style="height: 50px; width: auto; margin-right: 10px;">
        <span class="font-weight-bold">Bangladesh Railway</span>
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Time & Date Section (Stacked Layout) -->
    <div class="ml-auto text-white text-center" id="datetime" style="font-size: 20px; font-weight: bold; display: flex; flex-direction: column; align-items: center; margin-right: 20px;"></div>
</nav>
        <div class="container-fluid">
            <div class="row flex-xl-nowrap" style="min-height: 100vh;">
                <!-- Sidebar with Border-Right for Vertical Line -->
                <div class="col-12 col-md-3 col-xl-2 p-3 border-right" style="border-right: 2px solid #ccc;">
                    <div class="card shadow h-100;" style="background-color: #DFF6F0;">
                        <!-- Sidebar Header <div class="card-header bg-primary text-white text-center">
                            Sidebar Menu
                        </div> -->
                        <div class="card-body"> 
                            <!-- Profile Section -->
                            <form class="bd-search d-flex align-items-center mb-3">
                                <img src="{{ asset('images/Trainlogo.png') }}" alt="Profile Picture" class="rounded-circle border" width="40" height="40">
                                <span class="ml-2 font-weight-bold">John Doe</span>
                            </form>
                            <hr class="my-3">
                            
                            <!-- Navigation Buttons -->
                            <nav class="bd-links">
                            <div class="d-grid gap-2">
                                <a href="#" class="btn btn-success btn-block">Profile</a>
                                <!-- Train Section -->
                                <button class="btn btn-success btn-block mb-2" type="button" data-toggle="collapse" data-target="#trainOptions">
                                    Train
                                </button>
                                <div class="collapse mb-3 " id="trainOptions">
                                    <a href="{{ url('/train/edit') }}" class="btn sidebar-btn btn-block ml-3">Edit Train</a>
                                    <a href="{{ url('/train/create') }}" class="btn sidebar-btn btn-block ml-3">Add Train</a>
                                    <a href="{{ url('/train/show') }}" class="btn sidebar-btn btn-block ml-3">View Trains</a>
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
                        <div class="card-footer  border-0 text-center" style="background-color: #DFF6F0;">
                            <form>
                                <button type="submit" class="btn btn-danger btn-block">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Main Content Section -->
                <div class="col p-3">
                    <div class="d-flex justify-content-center align-items-start shadow p-3 mb-5 rounded" style="height: auto; padding-top: 50px; background-color: #DFF6F0; border: 1px solid #ccc;">
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