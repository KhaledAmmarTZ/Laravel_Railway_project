
<style>
    /* Make the table and column borders bolder */
    .custom-table {
        border: 4px solid black !important; /* Bolder Table Border */
    }

    .custom-table th, 
    .custom-table td {
        border: 3px solid black !important; /* Bolder Row & Column Borders */
        font-weight: bold; /* Make Text Bold */
        text-align: center; /* Center Align Text */
        padding: 10px; /* Add Padding */
    }

    .custom-table thead {
        background-color: #343a40; /* Dark Header */
        color: white;
        font-size: 18px; /* Bigger Header Font */
    }

    .custom-table tbody tr:nth-child(even) {
        background-color: #f2f2f2; /* Light Gray for Even Rows */
    }

    .status-available {
        color: green;
        font-weight: bold;
    }

    .status-unavailable {
        color: red;
        font-weight: bold;
        background-color: #ffcccc;
    }

    /* Custom Pagination Styling */
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

        <table class="table custom-table">
        <thead>
    <tr>
        <th>S</th>
        <th>Train Image</th> <!-- New Column -->
        <th>Train Name</th>
        <th>Departure Time</th>
        <th>Arrival Time</th>
        <th>Source</th>
        <th>Destination</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    @php $serial = ($trains->currentPage() - 1) * $trains->perPage() + 1; @endphp
    @foreach ($trains as $train)
        @foreach ($train->trainupdowns as $index => $updown)
            @php
                $currentTime = \Carbon\Carbon::now();
                $departureTime = \Carbon\Carbon::parse($updown->tdepdate . ' ' . $updown->tdeptime);
                $isAvailable = $departureTime->isFuture();
                $status = $isAvailable ? 'Available' : 'Unavailable';
                $statusClass = $isAvailable ? 'status-available' : 'status-unavailable';
            @endphp
            <tr>
                @if ($index == 0)
                    <td rowspan="{{ count($train->trainupdowns) }}">{{ $serial }}</td>
                    
                    <!-- Train Image Column -->
                    <td rowspan="{{ count($train->trainupdowns) }}">
                        @if ($train->train_image)
                        <img src="{{ asset('storage/' . $train->train_image) }}" alt="Train Image" class="img-fluid" style="max-width: 100px;">
                        @else
                            <p>No Image</p>
                        @endif
                    </td>
                    
                    <td rowspan="{{ count($train->trainupdowns) }}">{{ $train->trainname }}</td>
                @endif
                <td>
                    {{ \Carbon\Carbon::parse($updown->tdepdate)->format('d-m-Y') }} 
                    {{ \Carbon\Carbon::parse($updown->tdeptime)->format('h:i A') }}
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($updown->tarrdate)->format('d-m-Y') }} 
                    {{ \Carbon\Carbon::parse($updown->tarrtime)->format('h:i A') }}
                </td>                            
                <td>{{ $updown->tsource }}</td>
                <td>{{ $updown->tdestination }}</td>
                <td class="{{ $statusClass }}">
                    <strong>{{ $status }}</strong>
                </td>
                @if ($index == 0)
                    <td rowspan="{{ count($train->trainupdowns) }}">
                        <a href="{{ route('train.edit', $train->trainid) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('train.destroy', $train->trainid) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this train?')">Delete</button>
                        </form>
                    </td>
                @endif
            </tr>
        @endforeach
        @php $serial++; @endphp
    @endforeach
</tbody>

        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {!! $trains->onEachSide(1)->links('vendor.pagination.custom') !!}
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#search-input').on('keyup', function() {
            var query = $(this).val();
            var searchBy = $('#search-by').val();
            
            $.ajax({
                url: "{{ route('train.show') }}",
                method: 'GET',
                data: { search: query, search_by: searchBy },
                success: function(response) {
                    $('#train-table tbody').html(response);
                }
            });
        });
    });
</script>

