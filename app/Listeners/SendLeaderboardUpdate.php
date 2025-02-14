<?php

namespace App\Listeners;

use App\Events\TicketUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendLeaderboardUpdate
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TicketUpdated $event)
    {
        $ticket = $event->ticket;

        // Send the updated ticket data to the Socket.IO server
        Http::post('http://localhost:3000/leaderboard-updated', [
            'bottomTicket' => $ticket, // Assuming you want to send the updated ticket
        ]);
    }
}
