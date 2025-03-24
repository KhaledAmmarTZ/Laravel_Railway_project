@extends('layout.admin')

@section('title', 'Show Train')

@section('content')
<div class="card text-center" style="width: 100%; background-color: #f8f9fa; border: 1px solid #ccc;">
    <div class="card-header text-white" style="background-color: #005F56">
      Train Details
      @if(session('success'))
        <div class="alert alert-success text-center w-100">
            {{ session('success') }}
        </div>
      @endif
    </div>
    <div class="card-body">
        <div id="train-table">
            @include('train.partials.train_table')
           <div class="card-footer text-center d-flex justify-content-center align-items-center" style="background-color: #005F56">
        <form method="GET" action="{{ route('train.show') }}" id="search-form">
            <div class="mx-auto d-flex align-items-center">
                <select name="search_by" class="form-control mx-2" aria-label="Search by" id="search-by">
                    <option value="tname" {{ request()->search_by == 'tname' ? 'selected' : '' }}>Train Name</option>
                    <option value="tsource" {{ request()->search_by == 'tsource' ? 'selected' : '' }}>Source</option>
                    <option value="tdestination" {{ request()->search_by == 'tdestination' ? 'selected' : '' }}>Destination</option>
                </select>
                <input class="search-bar mx-2" type="search" id="search-input" name="search" value="{{ request()->search }}" placeholder="Search" aria-label="Search">
                <button class="search-btn" type="submit">Search</button>  
            </div>
        </form>
    </div> 
        </div>
        
    </div>

    
</div>

<script>
$(document).ready(function() {
    function fetchTrainData(url) {
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                $('#train-table').html(response);
            }
        });
    }

    // Live search event
    $('#search-input').on('keyup', function() {
        var query = $(this).val();
        var searchBy = $('#search-by').val();
        
        fetchTrainData("{{ route('train.show') }}?search=" + query + "&search_by=" + searchBy);
    });

    // Pagination click event
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        fetchTrainData(url);
    });
});
</script>

@endsection
