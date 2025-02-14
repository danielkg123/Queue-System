<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Ticket;
use App\Models\RunningText;
use App\Models\Carousel;
use App\Events\StatusUpdated;
use App\Events\LeaderboardUpdated;
use App\Helpers\NodeBroadcaster;
use Illuminate\Support\Facades\Http;


class LeaderboardController extends Controller
{

    public function index()
    {
        $today = Carbon::today();

        // Fetch the leaderboard data
        $bottomTicket = Ticket::whereDate('created_at', $today)
                              ->where('status', true)
                              ->orderBy('updated_at', 'desc')
                              ->first();

        $pembayaranTicket = Ticket::whereDate('created_at', $today)
                                  ->where('loket', 'Pembayaran')
                                  ->where('status', true)
                                  ->orderBy('updated_at', 'desc')
                                  ->first();

        $pengaduanTicket = Ticket::whereDate('created_at', $today)
                                 ->where('loket', 'Pengaduan')
                                 ->where('status', true)
                                 ->orderBy('updated_at', 'desc')
                                 ->first();

        $permohonanSambunganTicket = Ticket::whereDate('created_at', $today)
                                           ->where('loket', 'Permohonan Sambungan Baru')
                                           ->where('status', true)
                                           ->orderBy('updated_at', 'desc')
                                           ->first();

        // Prepare the data to send
        $data = [
            'bottomTicket' => $bottomTicket,
            'pembayaranTicket' => $pembayaranTicket,
            'pengaduanTicket' => $pengaduanTicket,
            'permohonanSambunganTicket' => $permohonanSambunganTicket
        ];
        $running_text = RunningText::where('status', 'active')->get();
        $carousel = Carousel::where('status', 'active')->get();
        Http::post('http://localhost:3000/update-leaderboard');


        return view('customer.leaderboard', compact('bottomTicket', 'pembayaranTicket', 'pengaduanTicket', 'permohonanSambunganTicket','running_text','carousel'));
    }
    

    public function getLatestTickets()
    {
        $today = Carbon::today();

        $bottomTicket = Ticket::whereDate('created_at', $today)
                            ->where('status', true)
                            ->orderBy('updated_at', 'desc')
                            ->first();

        $pembayaranTicket = Ticket::whereDate('created_at', $today)
                                ->where('loket', 'Pembayaran')
                                ->where('status', true)
                                ->orderBy('updated_at', 'desc')
                                ->first();

        $pengaduanTicket = Ticket::whereDate('created_at', $today)
                            ->where('loket', 'Pengaduan')
                            ->where('status', true)
                            ->orderBy('updated_at', 'desc')
                            ->first();

        $permohonanSambunganTicket = Ticket::whereDate('created_at', $today)
                                        ->where('loket', 'Permohonan Sambungan Baru')
                                        ->where('status', true)
                                        ->orderBy('updated_at', 'desc')
                                        ->first();

        return response()->json([
            'bottomTicket' => $bottomTicket,
            'pembayaranTicket' => $pembayaranTicket,
            'pengaduanTicket' => $pengaduanTicket,
            'permohonanSambunganTicket' => $permohonanSambunganTicket,
        ]);
    }

}
