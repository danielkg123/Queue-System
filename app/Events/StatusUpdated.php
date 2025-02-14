<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class StatusUpdated implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $bottomTicket;
    public $pembayaranTicket;
    public $pengaduanTicket;
    public $permohonanSambunganTicket;
    public $running_text;
    public $carousel;

    /**
     * Create a new event instance.
     */
    public function __construct($bottomTicket, $pembayaranTicket, $pengaduanTicket, $permohonanSambunganTicket, $running_text, $carousel)
    {
        $this->bottomTicket = $bottomTicket;
        $this->pembayaranTicket = $pembayaranTicket;
        $this->pengaduanTicket = $pengaduanTicket;
        $this->permohonanSambunganTicket = $permohonanSambunganTicket;
        $this->running_text = $running_text;
        $this->carousel = $carousel;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new Channel('status-updates');
    }
}
