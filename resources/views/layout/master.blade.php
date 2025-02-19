<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>@yield('title')</title>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        display: flex;
        flex-direction: column;
      }


      .navbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        margin: 0;
        width: 98.3%;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      }

      .container {
        flex: 1; /* Ensures content takes up remaining space */
      }

      footer {
        background-color: #081F5C;
        color: white;
        margin: 10px 17px;
        width: 98.3;
        text-align: center;
        border-radius: 5px 5px 0 0;
        font-size: 16px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      }

      .content {
        padding-top: 60px; /* Space below navbar */
      }
    </style>
  </head>
  <body style="background-color: #F0FFFC; 
        background-size: 100%; 
        background-repeat: no-repeat; 
        background-position: center 100px;
        padding-top: 100px;">
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light" style="height: 100px; margin: 10px 17px; border-radius: 5px; background-color: #081F5C; font-family: 'Sansation', sans-serif; font-weight: 700;">
        <a class="navbar-brand d-flex align-items-center text-white" style="margin-left: 20px; font-weight: bold;" href="#">
            <img src="{{ asset('images/Trainlogo.png') }}" alt="Logo" style="height: 50px; width: auto; margin-right: 10px;">
            <span class="font-weight-bold">Bangladesh Railway</span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="custom-btn mx-2 {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="custom-btn mx-2 {{ Request::is('train') ? 'active' : '' }}" href="{{ url('/train') }}">Train</a>
                </li>
                <li class="nav-item">
                    <a class="custom-btn mx-2" href="#">Ticket</a>
                </li>
                <li class="nav-item">
                    <a class="custom-btn mx-2" href="#">Contact Us</a>
                </li>
            </ul>
            <div class="ml-auto d-flex align-items-center">
                <div id="datetime" class="text-white text-center mr-3" style="font-size: 18px; font-weight: bold;"></div>
                <a href="#" class="login-btn mx-2">Login</a>
                <a href="#" class="register-btn mx-2">Register</a>
            </div>
        </div>
    </nav>
    
    <!-- Content Section -->
    <div class="content">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Bangladesh Railway. All rights reserved.</p>
        <p>
            <a href="#" class="text-white mx-3">Privacy Policy</a> | 
            <a href="#" class="text-white mx-3">Terms of Service</a>
        </p>
    </footer>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
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
