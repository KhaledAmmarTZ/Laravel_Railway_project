@extends('layouts.app')
@section('content')
    <style>
        /* body {
            background: linear-gradient(135deg, #c3c7cf, #dedfe1, #c4c4c4);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        } */
        /* .profile-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            animation: slideUp 0.8s ease;
        } */
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }
        .profile-img:hover {
            transform: scale(1.05);
        }
        .btn-edit {
            position: absolute;
            bottom: 10px;
            right: 10px;
            border-radius: 20px;
            padding: 0.5rem 1.5rem;
        }
        .skill-badge {
            margin: 0.3rem;
            padding: 0.6rem 1rem;
            border-radius: 20px;
            font-weight: 500;
        }
        .work-link {
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
            padding: 0.5rem;
            border-radius: 8px;
        }
        .work-link:hover {
            background: #f8f9fa;
            transform: translateX(5px);
        }
        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .section-title {
            border-left: 4px solid #0052D4;
            padding-left: 1rem;
            margin: 1.5rem 0;
        }
    </style>
    </head>
    <body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @session('msg')
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('msg') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endsession
                <div class="profile-container position-relative">
                    <div class="text-center position-relative">
                        <div class="avatar-wrapper d-flex flex-column align-items-center position-relative">
                            <div class="position-relative mb-3">
                                <img src="/images/{{ $user->images }}" alt="Uploaded Image" 
                                     class="profile-img rounded-circle" style="width: 150px; height: 150px;">
                            </div>
                            
                            <form action="{{ route('profile.upload') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="text-center">
                                    <input type="file" name="profile_image" id="profileImageInput" 
                                           class="d-none" onchange="this.form.submit()">
                                    <button type="button" class="btn btn-primary btn-sm" 
                                            onclick="document.getElementById('profileImageInput').click();">
                                        <i class="fas fa-camera me-2"></i>Edit Photo
                                    </button>
                                </div>
                            </form>
                        </div>
                        <h1 class="mt-4 mb-0 fw-bold">{{ $user->uname }}</h1>
                    </div>

                    <div class="mt-2">
                        <h4 class="section-title fw-semibold">About Me</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-3"><span class="fw-bold">Email:</span> {{$user->email}}</li>
                                    <li class="mb-3"><span class="fw-bold">Phone:</span> {{$user->phone}}</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-3"><span class="fw-bold">Address: </span>{{ $user->uplace }}</li>
                                    <li class="mb-3"><span class="fw-bold">Date of Birth: </span>{{ $user->dob }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2">
                        <h4 class="section-title fw-semibold">Edit Profile</h4>
                        <div class="d-grid gap-3">
                            <a href="{{ route('profile.edit', $user->uid) }}" class="work-link">
                                <i class="fas fa-globe me-2 text-primary"></i>Change Info
                            </a>
                            <a href="{{ route('password.request') }}" class="work-link">
                                <i class="fab fa-github me-2 text-dark"></i>Change Password
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
    
