<!DOCTYPE html>
<html>
<head>
    <title>Train Details</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Train Details (Preview)</h2>
    <p><strong>Train Name:</strong> {{ $trainData['trainname'] ?? 'N/A' }}</p>
    <p><strong>Number of Compartments:</strong> {{ $trainData['compartmentnumber'] ?? 'N/A' }}</p>
    <p><strong>Number of Updowns:</strong> {{ $trainData['updownnumber'] ?? 'N/A' }}</p>
    
    @if(!empty($trainData['train_image']))
        <p><strong>Train Image:</strong></p>
        <img src="{{ asset('storage/' . $trainData['train_image']) }}" alt="Train Image" width="200">
    @endif

    <h3>Compartments</h3>
    @if(!empty($trainData['compartments']))
        <table>
            <tr>
                    <th>Compartment Name</th>
                    <th>Type</th>
                    <th>Total Seats</th>
                    <th>Available Seats</th>
                    <th>Booked Seats</th>
                    <th>Price</th>
            </tr>
            @foreach ($trainData['compartments'] as $compartment)
                <tr>
                    <td>{{ $compartment['compartmentname'] ?? 'N/A' }}</td>
                    <td>{{ $compartment['seats'] ?? 'N/A' }}</td>
                    <td>{{ $compartment['type'] ?? 'N/A' }}</td>
                    <td>{{ isset($compartment['price']) ? '$' . number_format($compartment['price'], 2) : 'N/A' }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p>No compartment data available.</p>
    @endif

    <h3>Updowns (Routes)</h3>
    @if(!empty($trainData['updowns']))
        <table>
            <tr>
                <th>Source</th>
                <th>Destination</th>
                <th>Departure Time</th>
                <th>Arrival Time</th>
                <th>Departure Date</th>
                <th>Arrival Date</th>
            </tr>
            @foreach ($trainData['updowns'] as $updown)
                <tr>
                    <td>{{ $updown['tsource'] ?? 'N/A' }}</td>
                    <td>{{ $updown['tdestination'] ?? 'N/A' }}</td>
                    <td>{{ $updown['tdeptime'] ?? 'N/A' }}</td>
                    <td>{{ $updown['tarrtime'] ?? 'N/A' }}</td>
                    <td>{{ $updown['tdepdate'] ?? 'N/A' }}</td>
                    <td>{{ $updown['tarrdate'] ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p>No up-down route data available.</p>
    @endif
</body>
</html>
