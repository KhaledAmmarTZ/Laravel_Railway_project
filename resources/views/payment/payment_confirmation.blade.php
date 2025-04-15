<!DOCTYPE html>
<html>
<head>
    <title>Payment Confirmation</title>
</head>
<body>
    <h2>Dear Passenger,</h2>
    <p>Your payment has been successfully processed.</p>
    
    <p><strong>Payment Details:</strong></p>
    <ul>
        <li>
            <strong>Amount Paid:</strong>
            @isset($payment)
                ${{ number_format($payment['pamount'] / 100, 2) }}
            @else
                Payment information not available
            @endisset
        </li>
        <li>
            <strong>Payment Date:</strong>
            @isset($payment['pdate'])
                {{ $payment['pdate'] }}
            @else
                Not available
            @endisset
        </li>
        <li><strong>Payment Method:</strong> Stripe</li>
        <li>
            <strong>Total Tickets:</strong>
            @isset($payment['ticket_count'])
                {{ $payment['ticket_count'] }}
            @else
                Not available
            @endisset
        </li>
    </ul>

    <p><strong>Passenger Details:</strong></p>

    <p>Your booking is now <strong>Confirmed</strong>.</p>
    <p>Thank you for choosing our railway service.</p>
</body>
</html>