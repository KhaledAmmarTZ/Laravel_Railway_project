<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket - {{ $ticket->pnr }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .ticket { border: 2px solid #000; padding: 20px; max-width: 600px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; }
        .details { margin-bottom: 20px; }
        .row { display: flex; margin-bottom: 10px; }
        .col { flex: 1; }
        .barcode { text-align: center; margin-top: 20px; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <div class="logo">RAILWAY BOOKING SYSTEM</div>
            <div>E-TICKET</div>
        </div>
        
        <div class="details">
            <div class="row">
                <div class="col"><strong>PNR:</strong> {{ $ticket->pnr }}</div>
                <div class="col"><strong>Date:</strong> {{ now()->format('d/m/Y') }}</div>
            </div>
            <div class="row">
                <div class="col"><strong>Train No:</strong> {{ $ticket->passenger->trainid }}</div>
                <div class="col"><strong>Class:</strong> {{ $ticket->tclass }}</div>
            </div>
            <div class="row">
                <div class="col"><strong>From:</strong> {{ $ticket->passenger->tsource }}</div>
                <div class="col"><strong>To:</strong> {{ $ticket->passenger->tdest }}</div>
            </div>
            <div class="row">
                <div class="col"><strong>Departure:</strong> {{ $ticket->passenger->dptime }} on {{ $ticket->passenger->arrdate }}</div>
                <div class="col"><strong>Arrival:</strong> {{ $ticket->passenger->arrtime }}</div>
            </div>
            <div class="row">
                <div class="col"><strong>Passenger:</strong> {{ Auth::user()->name }}</div>
                <div class="col"><strong>Seat:</strong> {{ $ticket->tseat }} ({{ $ticket->tcompt }})</div>
            </div>
            <div class="row">
                <div class="col"><strong>Status:</strong> {{ $ticket->passenger->pstatus }}</div>
                <div class="col"><strong>Fare:</strong> ${{ number_format($ticket->passenger->price, 2) }}</div>
            </div>
        </div>
        
        <div class="barcode">
            <div>*{{ $ticket->pnr }}*</div>
            <small>Scan this barcode at the station</small>
        </div>
        
        <div class="footer">
            <p>This is an electronic ticket. Please carry a valid ID proof when traveling.</p>
            <p>© {{ date('Y') }} Railway Booking System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>