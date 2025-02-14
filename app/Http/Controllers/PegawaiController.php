<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class PegawaiController extends Controller
{
    public function index()
    {
        // Get the logged-in user's role
        $userRole = Auth::user()->role;

        Http::post('http://localhost:3000/update-leaderboard');
        // Pass the user's role to the view for conditional rendering
        return view('admin.index', compact('userRole'));
    }

    public function getNextTicket(Request $request)
    {
        // Fetch today's date
        $today = Carbon::today();

        // Get the logged-in user's role
        $userRole = Auth::user()->role;

        // Map roles to locket types
        $roleToLocket = [
            'sambunganbaru' => 'Permohonan Sambungan Baru',
            'pengaduan' => 'Pengaduan',
            'teller' => 'Pembayaran',
        ];

        // Determine the locket type for the user
        $locket = $roleToLocket[$userRole] ?? null;

        if (!$locket) {
            return response()->json([
                'success' => false,
                'message' => 'Locket type not found for the user role.',
            ]);
        }

        // Fetch the first available ticket for the current day and user's locket type
        $ticket = Ticket::whereDate('created_at', $today)
            ->where('loket', $locket)
            ->where('status', false)
            ->orderBy('created_at', 'asc')
            ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'No tickets available.',
            ]);
        }

        // Mark the ticket as 'Selesai' and associate it with the user
        $ticket->status = true;
        $ticket->user = Auth::user()->name;
        $ticket->lokasi = Auth::user()->counter;
        $ticket->save();
        Http::post('http://localhost:3000/update-leaderboard');
        return response()->json([
            'success' => true,
            'ticket' => $ticket,
        ]);
    }

    public function getTodayTicketCount(Request $request)
    {
        // Fetch today's start and end time based on the server's time zone
        $todayStart = Carbon::now()->startOfDay();
        $todayEnd = Carbon::now()->endOfDay();
    
        // Get the logged-in user's role
        $userRole = Auth::user()->role;
    
        // Map roles to locket types
        $roleToLocket = [
            'sambunganbaru' => 'Permohonan Sambungan Baru',
            'pengaduan' => 'Pengaduan',
            'teller' => 'Pembayaran',
        ];
    
        // Determine the locket type for the user
        $locket = $roleToLocket[$userRole] ?? null;
    
        if (!$locket) {
            return response()->json([
                'success' => false,
                'message' => 'Locket type not found for the user role.',
            ]);
        }
    
        // Query the count of tickets for today with status 'false'
        $ticketCount = Ticket::whereBetween('created_at', [$todayStart, $todayEnd])
            ->where('loket', $locket)
            ->where('status', 0) // Use 0 instead of false if 'status' is stored as an integer
            ->count();
    
        return response()->json([
            'success' => true,
            'count' => $ticketCount,
        ]);
    }
    
}
