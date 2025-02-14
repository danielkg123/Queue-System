<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Ticket extends Model
{
    protected $table = 'ticket';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = ['loket', 'user', 'no_antrian', 'tanggal_antrian','lokasi'];


    protected $casts = [
        'tanggal_antrian' => 'date',
        'status' => 'boolean',
    ];

    /**
     * Automatically set 'tanggal_antrian' to the current date when creating a ticket.
     */
    protected static function booted()
    {
        static::creating(function ($ticket) {
            if (!$ticket->tanggal_antrian) {
                $ticket->tanggal_antrian = Carbon::now()->toDateString();
            }
        });
    }
}
