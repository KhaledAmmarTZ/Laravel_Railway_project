<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Train Details</title>
    <style>
 body { font-family: Arial, sans-serif; }
        .container { width: 100%; max-width: 800px; margin: 0 auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .section { margin-top: 20px; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
        .timestamp { text-align: right; font-size: 14px; color: #555; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="timestamp">
            PDF Generated on: {{ now()->setTimezone('Asia/Dhaka')->format('d/m/Y h:i A') }}
        </div>

        <h2>Train Details: {{ $train->trainname }}</h2>
        
        @if($train->train_image)
            <img src="{{ public_path('storage/' . $train->train_image) }}" width="200" alt="Train Image">
        @endif

        <div class="section">
            <div class="title">Basic Information</div>
            <table>
                <tr><th>Train Name</th><td>{{ $train->trainname }}</td></tr>
                <tr><th>Total Compartment</th><td>{{ $train->compartmentnumber }}</td></tr>
            </table>
        </div>

        <div class="section">
            <div class="title">Compartments</div>
            <table>
                <tr>
                    <th>Compartment Name</th>
                    <th>Type</th>
                    <th>Total Seats</th>
                    <th>Available Seats</th>
                    <th>Booked Seats</th>
                    <th>Price</th>
                </tr>
                @foreach($train->traincompartments as $compartment)
                <tr>
                    <td>{{ $compartment->compartmentname }}</td>
                    <td>{{ $compartment->compartmenttype ?? 'N/A' }}</td>
                    <td>{{ $compartment->total_seats }}</td>
                    <td>{{ $compartment->available_seats }}</td>
                    <td>{{ $compartment->booked_seats }}</td>
                    <td>{{ $compartment->price ? '$' . number_format($compartment->price, 2) : 'N/A' }}</td>
                </tr>
                @endforeach
            </table>
        </div>

        <div class="section">
            <div class="title">Train Up-Down Routes</div>
            <table>
                <tr>
                    <th>Source</th>
                    <th>Destination</th>
                    <th>Departure Time</th>
                    <th>Arrival Time</th>
                    <th>Departure Date</th>
                    <th>Arrival Date</th>
                </tr>
                @foreach($train->trainupdowns as $updown)
                <tr>
                    <td>{{ $updown->tsource }}</td>
                    <td>{{ $updown->tdestination }}</td>
                    <td>{{ $updown->tdeptime }}</td>
                    <td>{{ $updown->tarrtime }}</td>
                    <td>{{ $updown->tdepdate }}</td>
                    <td>{{ $updown->tarrdate }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</body>
</html>
