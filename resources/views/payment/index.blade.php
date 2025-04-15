<!doctype html>
<html lang="en">
<head>
    <title>Payment</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="container col-md-6 mt-5">
        <div class="card">
            <div class="card-header">
                <h4>Payment</h4>
            </div>
            <div class="card-body">
                @if(session('msg'))
                    <div class="alert alert-success">
                        {{ session('msg') }}
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="timer mb-4">
                    <p>Remaining time to initiate your payment process: <strong>15:00</strong></p>
                </div>

                @php
                    $passenger = $passengers->first();
                    $ticket_count = $passengers->count();
                    $baseFare = $totalPrice;
                    $vat = 102 * $ticket_count;
                    $serviceCharge = 20 * $ticket_count;
                    $mealCost = $passenger->mealop ? 50 * $ticket_count : 0;
                    $total = $baseFare + $vat + $serviceCharge + $mealCost;
                @endphp
                <form action="{{ route('payment.charge') }}" method="post" id="payment-form">
                        @csrf
                <div class="route mb-4">
                    <h3>Journey Details</h3>
                    <div class="route-details">
                        <span class="source">{{ $passenger->tsource }}</span>
                        <i class="fas fa-arrow-right mx-2"></i>
                        <span class="destination">{{ $passenger->tdest }}</span>
                    </div>
                    <div class="route-details">
                        <span class="departure">{{ $passenger->dptime }}</span>
                        <span class="arrival ml-3">{{ $passenger->arrtime }}</span>
                    </div>
                    <h4 class="mt-3">{{ $train->trainname }} - {{ $passenger->tclass }}</h4>
                    <p>Meal Option: {{ $passenger->mealop ? 'Yes' : 'No' }}</p>
                </div>

                <div class="fare-details mb-4">
                    <h3>Fare Details</h3>
                    <table class="table">
                        <tr>
                            <th>Passenger x {{ $ticket_count }}</th>
                            <td>${{ $totalPrice }}</td>
                        </tr>
                        @if($passenger->mealop)
                            <tr>
                                <th>Meal Cost (x{{ $ticket_count }})</th>
                                <td>${{ $mealCost }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>VAT</th>
                            <td>${{ $vat }}</td>
                        </tr>
                        <tr>
                            <th>Service Charge</th>
                            <td>${{ $serviceCharge }}</td>
                        </tr>
                        <tr class="font-weight-bold">
                            <th>YOU PAY</th>
                            <td>${{ $total }}</td>
                        </tr>
                    </table>
                </div>

                <div class="payment-method">
                    
                        <div class="form-group">
                            <label for="card-element">Credit or debit card</label>
                            <div id="card-element" class="form-control mb-3"></div>
                            <div id="card-errors" class="text-danger" role="alert"></div>
                        </div>
                        
                        <input type="hidden" name="stripeToken" id="stripeToken">
                        <input type="hidden" name="uid" value="{{ $passenger->uid }}">
                        <input type="hidden" name="ticket_count" value="{{ $ticket_count }}">
                        <input type="hidden" name="mealop" value="{{ $passenger->mealop }}">
                        <input type="hidden" name="pamount" value="{{ $total * 100 }}">
                        
                        <button type="button" class="btn btn-primary btn-block" onclick="createToken()">Pay Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    
    <script>
        // Stripe Integration
        var stripe = Stripe("{{ env('STRIPE_KEY') }}");
        var elements = stripe.elements();
        var cardElement = elements.create('card');
        cardElement.mount('#card-element');

        function createToken() {
            stripe.createToken(cardElement).then(function(result) {
                if (result.error) {
                    document.getElementById('card-errors').textContent = result.error.message;
                } else {
                    document.getElementById('stripeToken').value = result.token.id;
                    document.getElementById('payment-form').submit();
                }
            });
        }

        // Timer functionality
        let timeLimit = 15 * 60; // 1 minute

    function updateTimer() {
        const timerElement = document.querySelector('.timer strong');
        if (timerElement) {
            const minutes = Math.floor(timeLimit / 60);
            const seconds = timeLimit % 60;
            timerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

            if (--timeLimit < 0) {
                clearInterval(timerInterval);
                alert('Payment time has expired. Your booking has been canceled.');

                // Call the backend to cancel the booking
                cancelBooking();
            }
        }
    }

    function cancelBooking() {
        const uid = document.getElementById('uid').value; // Get UID from hidden input

        fetch('/cancel-booking', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ uid: uid }) // Send UID properly
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/passenger/create'; // Redirect after cancellation
            } else {
                alert('Failed to cancel booking: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Something went wrong.');
        });
    }

    let timerInterval = setInterval(updateTimer, 1000);
    </script>
</body>
</html>