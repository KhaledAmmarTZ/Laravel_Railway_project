<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
        <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
        margin: 10px auto;
        width: calc(100% - 34px);
        border-radius: 5px;
        background-color: #005F56;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }


      .container {
        flex: 1; /* Ensures content takes up remaining space */
      }

      footer {
        background-color: #005F56;
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
    @yield('styles')
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body style="background-color: #F0FFFC; 
        background-size: 100%; 
        background-repeat: no-repeat; 
        background-position: center;">

        
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light" style="height: 100px; margin: 10px 17px; border-radius: 5px; background-color: #005F56; font-family: 'Sansation', sans-serif; font-weight: 700;">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/Trainlogo.png') }}" alt="Logo" style="height: 50px; width: auto; margin-right: 10px;">
                <span class="font-weight-bold" style="color: white;">Bangladesh Railway</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
        
                <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto mx-auto d-flex justify-content-center">
                        @auth
                            @if(Auth::user()->userType == 'user')
                            <li class="nav-item">
                                <a class="nav-link" 
                                href="{{ route('passenger.index') }}"
                                style="color: white;">
                                    <i class="fas fa-home me-1" style="color: white;"></i> Dashboard
                                </a>
                            </li>
                            <div class="ml-5"></div>
                            <li class="nav-item">
                                <a class="nav-link" 
                                href="{{ route('ticket.index') }}"
                                style="color: white;">
                                    <i class="fas fa-search me-1" style="color: white;"></i> My Tickets
                                </a>
                            </li>  
                            <div class="ml-5"></div>
                            
                            @else
                                {{--  Admin navbar if any goes here --}}                          
                            @endif
                        @endauth
                    </ul>
        
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" style="color: white;" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->uname }}
                                </a>
        
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
        
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>        
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://js.stripe.com/v3/"></script>
    @yield('scripts')
</body>
</html>
