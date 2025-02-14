<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use RawPrinter;
use Illuminate\Support\Facades\Auth;
use App\Events\TicketUpdated;
use Illuminate\Support\Facades\Http;

class TicketController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'loket' => 'required|in:Pembayaran,Pengaduan,Permohonan Sambungan Baru',
        ]);

        // Assign user to 'none' (you can modify this logic as needed)
        $validated['user'] = 'none';

        // Determine the ticket prefix based on 'loket'
        $prefix = match ($validated['loket']) {
            'Pembayaran' => 'C',
            'Pengaduan' => 'A',
            'Permohonan Sambungan Baru' => 'B',
        };

        // Get today's date
        $today = Carbon::today()->toDateString();

        // Find the last ticket for this 'loket' today and calculate the next number
        $lastTicket = Ticket::where('tanggal_antrian', $today)
            ->where('loket', $validated['loket'])
            ->orderBy('no_antrian', 'desc')
            ->first();

        $nextAntrian = $lastTicket ? intval(substr($lastTicket->no_antrian, 1)) + 1 : 1;

        // Generate the ticket ID and formatted queue number
        $formattedAntrian = $prefix . str_pad($nextAntrian, 3, '0', STR_PAD_LEFT);
        // Add 'no_antrian' and 'ticket_id' to the validated data
        $validated['no_antrian'] = $formattedAntrian;

        // Create the ticket
        $ticket = Ticket::create($validated);
        
        Http::post('http://localhost:3000/update-leaderboard');
        // Return success message with ticket details
        // Modify the session data to be an array
        return redirect()->back()->with('success', [
            'no_antrian' => $ticket->no_antrian,
            'loket' => $ticket->loket,
            'date' => $ticket->created_at,
        ]);

    }


    /**
     * Update ticket status to 'Selesai'.
     */
    public function updateStatus($ticketId)
    {
        $ticket = Ticket::find($ticketId);
    
        if ($ticket) {
            $ticket->status = true; // Update the status to 'Selesai'
            $ticket->user = Auth::user()->name; // Set the current logged-in user's name
            $ticket->save();
    
            // Send an HTTP request to the Node.js server to broadcast the ticket update
            Http::post('http://localhost:3000/update-leaderboard');
    
            // Trigger the local event for ticket update
            event(new TicketUpdated($ticket));
    
            return redirect()->back()->with('success', 'Ticket status updated successfully and assigned to ' . Auth::user()->name . '!');
        }
    
        return redirect()->back()->with('error', 'Ticket not found.');
    }

}
