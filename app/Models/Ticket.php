<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'seat_number',
        'price',
        'purchase_date',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function session() {
        return $this->belongsTo(Session::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
