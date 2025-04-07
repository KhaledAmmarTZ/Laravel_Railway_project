@extends('layouts.app')
@section('title')
    Search Tickets
@endsection

@section('content')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">

            @session('error')
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endsession

            <div class="card shadow-lg">
                <div class="card-header text-white" style="background-color: #005F56"> 
                    <h4 class="mb-0">Plan Your Journey</h4>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('passenger.searchForm') }}" method="GET">
                        @csrf

                        <div class="mb-4">
                            <label for="tsource" class="form-label fw-bold">From Station</label>
                            <select class="form-control select2" id="tsource" name="tsource" required></select>
                            @error('tsource')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tdestination" class="form-label fw-bold">To Station</label>
                            <select class="form-control select2" id="tdestination" name="tdestination" required></select>
                            @error('tdestination')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="arrdate" class="form-label fw-bold">Date of Journey</label>
                            <div class="input-group">
                                <input type="date" 
                                       name="arrdate" 
                                       id="arrdate" 
                                       class="form-control"
                                       value="{{ old('arrdate') }}"
                                       required 
                                       min="{{ \Carbon\Carbon::today()->toDateString() }}"
                                       max="{{ \Carbon\Carbon::today()->addDays(20)->toDateString() }}">
                                <span class="input-group-text">
                                    <i class="bi bi-calendar3"></i>
                                </span>
                            </div>
                            @error('arrdate')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn search-btn">
                                <i class="bi bi-search me-2"></i>Search Trains
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    var path = "{{ route('autocomplete') }}"; // Same backend route for both fields

    $('#tsource').select2({
        placeholder: 'Select where your are',
        ajax: {
            url: path,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term, type: 'tsource' }; // Pass 'type' to differentiate requests
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            id: item.name,
                            text: item.name
                        };
                    })
                };
            }
        }
    });

    // Initialize Select2 for Destination
    $('#tdestination').select2({
        placeholder: 'Select where you are going',
        ajax: {
            url: path,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term, type: 'tdestination' }; // Pass 'type' to differentiate requests
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            id: item.name,
                            text: item.name
                        };
                    })
                };
            }
        }
    });
</script>
@endsection

@section('styles')
<style>
    .select2-container--default .select2-selection--single {
        height: 45px;
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 43px;
    }
    .card {
        border-radius: 15px;
    }
    .input-group-text {
        background-color: #f8f9fa;
    }
</style>
@endsection