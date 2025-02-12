@extends('layout.admin')
@section('title')
    Admin Profile
@endsection
@section('content')
    <div class="w-100 p-4">
        <!-- Top Section: Profile Pic & User Info -->
        <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
            <div class="d-flex align-items-center">
                <img src="{{ asset('images/Trainlogo.png') }}" class="border custom-rounded" width="220" height="220" alt="Profile Picture">
                <div class="ms-4">
                    <h3 class="mb-1">John Doe</h3>
                    <p class="text-muted mb-1">Super Admin</p>
                    <p class="text-muted mb-0">admin@example.com</p>
                </div>
            </div>
        </div>

        <hr>

        <!-- Additional Details Section -->
        <div class="row">
            <div class="col-md-6">
                <p><strong>Phone:</strong> +1234567890</p>
                <p><strong>Date of Birth:</strong> Jan 1, 1990</p>
            </div>
            <div class="col-md-6">
                <p><strong>NID:</strong> 123456789</p>
                <p><strong>Place:</strong> New York, USA</p>
            </div>
        </div>
    </div>
@endsection