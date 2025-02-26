@foreach ($trains as $train)
    @foreach ($train->trainupdowns as $updown)
        <tr>
            <td>{{ $train->trainname }}</td>
            <td>{{ \Carbon\Carbon::parse($updown->tarrtime)->format('d-m-Y h:i A') }}</td>
            <td>{{ \Carbon\Carbon::parse($updown->tdeptime)->format('d-m-Y h:i A') }}</td>
            <td>{{ $updown->tsource }}</td>
            <td>{{ $updown->tdestination }}</td>
            <td>
                <ul>
                    @foreach ($train->traincompartments as $compartment)
                        <li>Name: {{ $compartment->compartmentname }} (Seats: {{ $compartment->seatnumber }}) (Type: {{ $compartment->compartmenttype }})</li>
                    @endforeach
                </ul>
            </td>
        </tr>
    @endforeach
@endforeach

