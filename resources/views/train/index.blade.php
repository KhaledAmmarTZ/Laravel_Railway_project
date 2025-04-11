@extends('layout.master')

@section('title')
    Index Page
@endsection

@section('content')
    <style>
        .custom-table {
            border: 4px solid black !important;
        }

        .custom-table th, 
        .custom-table td {
            border: 3px solid black !important;
            font-weight: bold;
            text-align: center;
            padding: 10px;
        }

        .custom-table thead {
            background-color: #343a40;
            color: white;
            font-size: 18px;
        }

        .custom-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .pagination {
            justify-content: center;
            margin-top: 20px;
        }

        .pagination .page-item.active .page-link {
            background-color: #005F56;
            border-color: #005F56;
            color: white;
        }

        .pagination .page-item .page-link {
            color: black;
            font-weight: bold;
            border: 2px solid black;
        }

        .pagination .page-item.disabled .page-link {
            color: gray;
            border-color: #ccc;
        }
    </style>

    <div class="card text-center" style="width: 98%; margin-left: 1%; background-color: #f8f9fa; border: 2px solid #000;">
        <div class="card-header text-white" style="background-color: #005F56">
            Train List
        </div>
        <div class="card-body">
            <table class="table custom-table" id="train-table">
                <thead>
                    <tr>
                        <th>S</th>
                        <th>Train Name</th>
                        <th>Arrival Time</th>
                        <th>Departure Time</th>
                        <th>Source</th>
                        <th>Destination</th>
                    </tr>
                </thead>
                <tbody>
                    @php $serial = ($updowns->currentPage() - 1) * $updowns->perPage() + 1; @endphp
                    @foreach ($updowns as $updown)
                        <tr>
                            <td>{{ $serial }}</td>
                            <td>{{ $updown->train->trainname }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($updown->tarrdate)->format('d-m-Y') }} 
                                {{ \Carbon\Carbon::parse($updown->tarrtime)->format('h:i A') }}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($updown->tdepdate)->format('d-m-Y') }} 
                                {{ \Carbon\Carbon::parse($updown->tdeptime)->format('h:i A') }}
                            </td>
                            <td>{{ $updown->tsource }}</td>
                            <td>{{ $updown->tdestination }}</td>
                        </tr>
                        @php $serial++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {!! $updowns->onEachSide(1)->links('vendor.pagination.custom') !!}
        </div>
    </div>

    <div class="card-footer text-center d-flex justify-content-center align-items-center" style="background-color: #005F56; width: 98%; margin-left: 1%;">
        <form method="GET" action="{{ route('train.index') }}" id="search-form">
            <div class="mx-auto d-flex align-items-center">
                <!-- Dropdown to select search type -->
                <select name="search_by" class="form-control mx-2" aria-label="Search by" id="search-by">
                    <option value="tname" {{ request()->search_by == 'tname' ? 'selected' : '' }}>Train Name</option>
                    <option value="tsource" {{ request()->search_by == 'tsource' ? 'selected' : '' }}>Source</option>
                    <option value="tdestination" {{ request()->search_by == 'tdestination' ? 'selected' : '' }}>Destination</option>
                </select>

                <!-- Input field for search term -->
                <input class="search-bar mx-2" type="search" id="search-input" name="search" value="{{ request()->search }}" placeholder="Search" aria-label="Search">
                <button class="search-btn" type="submit">Search</button>  
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#search-input').on('keyup', function() {
            var query = $(this).val();
            var searchBy = $('#search-by').val();
            
            $.ajax({
                url: "{{ route('train.index') }}",
                method: 'GET',
                data: {
                    search: query,
                    search_by: searchBy,
                },
                success: function(response) {
                    $('#train-table tbody').html(response);
                }
            });
        });
    });
</script>
@endsection
