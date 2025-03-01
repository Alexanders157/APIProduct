<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $table = 'sessionss';

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($session) {
            if ($session->movie && $session->start_time && !$session->end_time) {
                $duration = $session->movie->duration;
                $session->end_time = $session->start_time->copy()->addMinutes($duration);
            }

            if ($session->hall_id && $session->start_time && $session->end_time) {
                $conflictingSession = Session::where('hall_id', $session->hall_id)
                    ->where(function ($query) use ($session) {
                        $query->whereBetween('start_time', [$session->start_time, $session->end_time])
                            ->orWhereBetween('end_time', [$session->start_time, $session->end_time])
                            ->orWhere(function ($query) use ($session) {
                                $query->where('start_time', '<', $session->end_time)
                                    ->where('end_time', '>', $session->start_time);
                            });
                    })
                    ->when($session->exists, function ($query) use ($session) {
                        $query->where('id', '!=', $session->id);
                    })
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

    public function scopeDate($query, $date)
    {
        return $query->whereDate('start_time', $date);
    }

    public function scopeMovie($query, $movie)
    {
        return $query->where('movie_id', $movie);
    }
}
