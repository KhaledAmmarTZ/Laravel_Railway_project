@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Ticket Details</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>Ticket Information</h4>
                    <p><strong>PNR:</strong> {{ $ticket->pnr }}</p>
                    <p><strong>Train Number:</strong> {{ $ticket->passenger->trainid }}</p>
                    <p><strong>Class:</strong> {{ $ticket->tclass }}</p>
                    <p><strong>Seat Number:</strong> {{ $ticket->tseat }}</p>
                    <p><strong>Compartment:</strong> {{ $ticket->tcompt }}</p>
                </div>
                <div class="col-md-6">
                    <h4>Journey Details</h4>
                    <p><strong>From:</strong> {{ $ticket->passenger->tsource }}</p>
                    <p><strong>To:</strong> {{ $ticket->passenger->tdest }}</p>
                    <p><strong>Departure:</strong> {{ $ticket->passenger->dptime }} on {{ $ticket->passenger->arrdate }}</p>
                    <p><strong>Arrival:</strong> {{ $ticket->passenger->arrtime }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-success">{{ $ticket->passenger->pstatus }}</span></p>
                </div>
            </div>
            
            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('ticket.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Tickets
                </a>
                <div class="btn-group">
                    <a href="{{ route('ticket.download', $ticket->pnr) }}" class="btn btn-success">
                        <i class="fas fa-download"></i> Download Ticket
                    </a>
                    @if($ticket->passenger->pstatus == 'confirmed')
                    <a href="#" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('cancel-form').submit();">
                        <i class="fas fa-times"></i> Cancel Ticket
                    </a>
                    <form id="cancel-form" action="{{ route('ticket.cancel', $ticket->pnr) }}" method="POST" style="display: none;">
                        @csrf
                        @method('PATCH')
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection