@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">My Tickets</h1>
    
    @if(!$tickets || $tickets->isEmpty())
        <div class="alert alert-info">
            You don't have any tickets yet.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>PNR</th>
                        <th>Train Name</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Departure</th>
                        <th>Class</th>
                        <th>Seat</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->pnr }}</td>
                        <td>{{ $ticket->passenger->train->trainname }}</td>
                        <td>{{ $ticket->passenger->tsource }}</td>
                        <td>{{ $ticket->passenger->tdest }}</td>
                        <td>{{ $ticket->passenger->dptime }}<br>{{ $ticket->passenger->arrdate }}</td>
                        <td>{{ $ticket->tclass }}</td>
                        <td>{{ $ticket->tseat }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('ticket.show', $ticket->pnr) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('ticket.download', $ticket->pnr) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection