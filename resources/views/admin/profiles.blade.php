@extends('layout.admin')

@section('title')
    Admin Profile
@endsection

@section('content')
    <div class="w-100 p-4 blurry-card" style="border-radius: 5px;">
        <!-- Top Section: Profile Pic & User Info -->
        <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
            <div class="d-flex align-items-center">
                <img src="{{ auth()->guard('admin')->user()->admin_image 
                    ? asset('storage/' . auth()->guard('admin')->user()->admin_image) 
                    : asset('images/default-profile.png') }}" 
                    class="border custom-rounded img-fluid" width="220" height="220" 
                    alt="Profile Picture">
                <div class="ms-4" style="margin-left: 30px;">
                    <!-- Display the logged-in super admin's name -->
                    <h3 class="mb-1">{{ auth()->guard('admin')->user()->name }}</h3>
                    
                    <!-- Display the logged-in super admin's role -->
                    <p class="text-muted mb-1">{{ ucfirst(auth()->guard('admin')->user()->role) }}</p>
                    
                    <!-- Display the logged-in super admin's email -->
                    <p class="text-muted mb-0">{{ auth()->guard('admin')->user()->email }}</p>
                </div>
            </div>
        </div>

        <hr>

        <!-- Additional Details Section -->
        <div class="row">
            <div class="col-md-6">
                <!-- Display super admin's phone number -->
                <p><strong>Phone:</strong> {{ auth()->guard('admin')->user()->phoneNumber }}</p>
                
                <!-- Display super admin's date of birth -->
                <p><strong>Date of Birth:</strong> {{ \Carbon\Carbon::parse(auth()->guard('admin')->user()->date_of_birth)->format('M d, Y') }}</p>
            </div>
            <div class="col-md-6">
                <!-- Display super admin's NID -->
                <p><strong>NID:</strong> {{ auth()->guard('admin')->user()->admin_nid }}</p>
                
                <!-- Display super admin's place -->
                <p><strong>Place:</strong> {{ auth()->guard('admin')->user()->place }}</p>
            </div>
        </div>

        <hr style="width: 100%; height: 2px; background-color: black; border: none;">
<style>
        .blurry-card {
    position: relative;
    background-color: rgba(248, 249, 250, 0.5); /* Add a semi-transparent background */
    backdrop-filter: blur(10px); /* Apply the blur effect */
    }
    </style>
       
@endsection