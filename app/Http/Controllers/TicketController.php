<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Train;
use App\Models\Passenger;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    /**
     * Display a listing of the user's tickets.
     */
    public function index()
    {
        // Correct relationship query with specific database connection
        $tickets = Ticket::whereHas('passenger', function($query) {
            $query->where('user_id', Auth::id());
        })
        ->with('passenger')
        ->get();

        // Fetch train data as usual, assuming it's in the default database
        $trains = Train::select('trainid', 'trainname')->get();

        return view('ticket.index', compact('tickets', 'trains'));
    }

    /**
     * Display the specified ticket.
     */
    public function show($pnr)
    {
        // Ensure the query is on the correct connection for the user and the ticket
        $ticket = Ticket::with('passenger')
            ->where('pnr', $pnr)
            ->whereHas('passenger', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        return view('ticket.show', compact('ticket'));
    }

    /**
     * Download the ticket as PDF.
     */
    public function download($pnr)
    {
        // Ensure the query is on the correct connection for the user and the ticket
        $ticket = Ticket::with('passenger')
            ->where('pnr', $pnr)
            ->whereHas('passenger', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        // Load PDF view for ticket
        $pdf = Pdf::loadView('ticket.pdf', compact('ticket'));
        
        return $pdf->download("ticket-{$ticket->pnr}.pdf");
    }

    /**
     * Cancel the specified ticket.
     */
    public function cancel($pnr)
    {
        // Ensure the query is on the correct connection for the user and the ticket
        $ticket = Ticket::with('passenger')
            ->where('pnr', $pnr)
            ->whereHas('passenger', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        // Update passenger status to 'cancelled' on the correct database
        $ticket->passenger->update(['pstatus' => 'cancelled']);

        // Optionally update ticket status if you have that field
        $ticket->update(['status' => 'cancelled']);

        return redirect()->route('ticket.index')
            ->with('success', 'Ticket has been cancelled successfully');
    }
}
