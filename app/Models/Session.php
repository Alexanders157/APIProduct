<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $table = 'sessionss';

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($session) {
            if ($session->movie && $session->start_time && !$session->end_time) {
                $session->end_time = $session->start_time->addMinutes($session->movie->duration);
            }

            if ($session->hall_id && $session->start_time && $session->end_time) {
                $conflictingSession = Session::where('hall_id', $session->hall_id)
                    ->where('start_time', '<', $session->end_time)
                    ->where('end_time', '>', $session->start_time)
                    ->when($session->exists, fn ($query) => $query->where('id', '!=', $session->id))
                    ->first();

                if ($conflictingSession) {
                    throw new \Exception('Зал занят в указанный временной промежуток');
                }
            }
        });
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
