<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Passenger;
use App\Models\Train;
use App\Models\Ticket;
use App\Models\User;
use App\Mail\ConfirmationTicket;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;

class PaymentController extends Controller
{
    /**
     * Show the payment page with passenger details.
     */
    public function showPaymentPage($pnr)
    {
        // Retrieve the authenticated user's ID from the `mysql1` connection
        $userId = DB::connection('mysql1')->table('users')->where('id', Auth::id())->value('id');

        // Retrieve passenger data using 'user_id' as the identifier and filter by 'pstatus = waiting'
        $passengers = Passenger::where('user_id', $userId)->where('pstatus', 'waiting')->get();
        $passenger = $passengers->first(); // Get the first passenger

        if (!$passenger) {
            return redirect()->route('passenger.index')->with('error', 'No waiting passenger found.');
        }

        // Calculate the total price of all waiting passengers
        $totalPrice = Passenger::where('user_id', $userId)->where('pstatus', 'waiting')->sum('price');

        // Check if the train exists for the passenger's trainid
        $train = Train::find($passenger->trainid);

        if (!$train) {
            return redirect()->route('train.index')->with('error', 'Train not found for this passenger.');
        }

        // Store data in session
        session([
            'passenger' => $passenger,
            'passengers' => $passengers,
            'train' => $train
        ]);

        // Return the payment view with necessary data
        return view('payment.index', compact(['passengers', 'train', 'totalPrice']));
    }

    /**
     * Process the payment and store it in the database.
     */
    public function charge(Request $request)
    {
        // Validate the payment data
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $request->validate([
            'pamount' => 'required|numeric',
            'user_id' => 'required',
            'ticket_count' => 'required|integer|min:1|max:4',
            'mealop' => 'required|boolean',
        ]);

        $amount = (int) $request['pamount'];

        // Process the Stripe payment
        $charge = $stripe->charges->create([
            'amount' => $amount, // Convert to cents
            'source' => $request->stripeToken,
            'currency' => 'usd',
            'description' => 'Payment completed',
        ]);

        // Create the payment record
        Payment::create([
            'user_id' => Auth::id(),
            'pdate' => now(), // Use current date/time
            'pamount' => $amount,
            'pmethod' => 'stripe',
            'ticket_count' => $request->ticket_count,
            'mealop' => $request->mealop,
        ]);

        // Update passenger status to 'confirmed'
        Passenger::where('user_id', $request->user_id)
            ->where('pstatus', 'waiting')
            ->update(['pstatus' => 'confirmed']);

        // Send confirmation email
        $user = DB::connection('mysql1')->table('users')->where('id', Auth::id())->first();
        $passenger = session('passenger');
        Mail::to($user->email)->send(new ConfirmationTicket(
            $user,
            $passenger,  // Single passenger
            [
                'user_id' => Auth::id(),
                'pdate' => now(),
                'pamount' => $amount,
                'pmethod' => 'stripe',
                'ticket_count' => $request->ticket_count,
                'mealop' => $request->mealop,
            ]
        ));

        // Update train compartment seat availability
        $tra = session('tra');
        $seat_remain = $tra['available_seats'] - $request->ticket_count;

        if ($tra['direction'] == 'up') {
            DB::table('traincompartments')
                ->where('trainid', $tra['trainid'])
                ->where('compartmenttype', $tra['tclass'])
                ->update(['available_seats_up' => $seat_remain]);
        } else {
            DB::table('traincompartments')
                ->where('trainid', $tra['trainid'])
                ->where('compartmenttype', $tra['tclass'])
                ->update(['available_seats_down' => $seat_remain]);
        }

        // Create tickets for passengers
        $traincompartment = DB::table('traincompartments')
            ->where('trainid', $tra['trainid'])
            ->where('compartmenttype', $tra['tclass'])
            ->first();

        $passengers = session('passengers');
        $tseat = $traincompartment->total_seats - $tra['available_seats'] + 1;

        foreach ($passengers as $passenger) {
            Ticket::create([
                'pnr' => $passenger->pnr,
                'tclass' => $tra['tclass'],
                'tseat' => $tseat,
                'tcompt' => $traincompartment->compartmentname
            ]);
            $tseat++;
        }

        // Redirect to the payment success page
        return redirect()->route('ticket.index')
            ->with('success', 'Payment has been successfully processed. Your ticket is ready!');
    }
}