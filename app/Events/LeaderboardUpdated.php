<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeaderboardUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bottomTicket;
    public $pembayaranTicket;
    public $pengaduanTicket;
    public $permohonanSambunganTicket;
    public $runningText;
    public $carousel;

    // Constructor to initialize data
    public function __construct($bottomTicket, $pembayaranTicket, $pengaduanTicket, $permohonanSambunganTicket, $runningText, $carousel)
    {
        $this->bottomTicket = $bottomTicket;
        $this->pembayaranTicket = $pembayaranTicket;
        $this->pengaduanTicket = $pengaduanTicket;
        $this->permohonanSambunganTicket = $permohonanSambunganTicket;
        $this->runningText = $runningText;
        $this->carousel = $carousel;
    }

    // Broadcast on this channel
    public function broadcastOn()
    {
        return new Channel('leaderboard');
    }

    // Broadcast the data
    public function broadcastWith()
    {
        return [
            'bottomTicket' => $this->bottomTicket,
            'pembayaranTicket' => $this->pembayaranTicket,
            'pengaduanTicket' => $this->pengaduanTicket,
            'permohonanSambunganTicket' => $this->permohonanSambunganTicket,
            'runningText' => $this->runningText,
            'carousel' => $this->carousel,
        ];
    }
}

