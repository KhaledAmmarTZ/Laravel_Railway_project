@extends('layout.admin')

@section('title')
    Super Admin Profile
@endsection

@section('content')
    <div class="w-100 p-4">
        <!-- Top Section: Profile Pic & User Info -->
        <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
            <div class="d-flex align-items-center">
                <img src="{{ asset('images/Trainlogo.png') }}" class="border custom-rounded" width="220" height="220" alt="Profile Picture">
                <div class="ms-4">
                    <!-- Display the logged-in super admin's name -->
                    <h3 class="mb-1">{{ auth()->guard('superadmin')->user()->admin_name }}</h3>
                    
                    <!-- Display the logged-in super admin's role -->
                    <p class="text-muted mb-1">{{ ucfirst(auth()->guard('superadmin')->user()->admin_role) }}</p>
                    
                    <!-- Display the logged-in super admin's email -->
                    <p class="text-muted mb-0">{{ auth()->guard('superadmin')->user()->admin_email }}</p>
                </div>
            </div>
        </div>

        <hr>

        <!-- Additional Details Section -->
        <div class="row">
            <div class="col-md-6">
                <!-- Display super admin's phone number -->
                <p><strong>Phone:</strong> {{ auth()->guard('superadmin')->user()->admin_phoneNumber }}</p>
                
                <!-- Display super admin's date of birth -->
                <p><strong>Date of Birth:</strong> {{ \Carbon\Carbon::parse(auth()->guard('superadmin')->user()->admin_date_of_birth)->format('M d, Y') }}</p>
            </div>
            <div class="col-md-6">
                <!-- Display super admin's NID -->
                <p><strong>NID:</strong> {{ auth()->guard('superadmin')->user()->admin_nid }}</p>
                
                <!-- Display super admin's place -->
                <p><strong>Place:</strong> {{ auth()->guard('superadmin')->user()->admin_place }}</p>
            </div>
        </div>
    </div>
@endsection
