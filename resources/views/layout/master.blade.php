<!doctype html>
<html lang="en">
  <head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap 4.6.2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  </head>
  <body style="background-color: #F0FFFC; 
               background-image: url('{{ asset('images/Group.png') }}'); 
               background-size: cover; 
               background-repeat: no-repeat; 
               background-position: center 100px;">
    
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light" style="height: 100px; margin: 10px 17px; border-radius: 5px; background-color: #005F56; font-family: 'Sansation', sans-serif; font-weight: 700;">
            <a class="navbar-brand d-flex align-items-center text-white" style="margin-left: 20px; font-weight: bold;" href="#">
                <img src="{{ asset('images/Trainlogo.png') }}" alt="Logo" style="height: 50px; width: auto; margin-right: 10px;">
                <span class="font-weight-bold">Bangladesh Railway</span>
            </a>
       
            <!-- Navbar Toggler -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
              <div class="navbar-nav">
                <a class="nav-item nav-link active text-white mx-2" href="#">Home</a>
                <a class="nav-item nav-link text-white mx-2" href="#">Ticket</a>
                <a class="nav-item nav-link text-white mx-2" href="#">Train</a>
              </div>
            </div>
            
            <!-- Time, Date, and Login/Register -->
            <div class="ml-auto d-flex align-items-center">
                <div id="datetime" class="text-white text-center mr-3" style="font-size: 18px; font-weight: bold;"></div>
                <a href="#" class="btn btn-light mx-2">Login</a>
                <a href="#" class="btn btn-primary mx-2">Register</a>
            </div>
        </nav>
        
        <div>
            @yield('content')
        </div>
    </div>

    <!-- jQuery (Full Version, Must Load First) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Popper.js (Required for Bootstrap) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

    <!-- Bootstrap JS (Includes Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function updateDateTime() {
            let now = new Date();
            let date = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            let time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

            document.getElementById('datetime').innerHTML = `<span style="font-size: 22px;">${time}</span> <span style="font-size: 16px; margin-top: 5px;">${date}</span>`;
        }
        setInterval(updateDateTime, 1000); 
        updateDateTime();
    </script>
  </body>
</html>
