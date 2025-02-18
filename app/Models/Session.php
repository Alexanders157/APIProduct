<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{

    use HasFactory;
    /*protected $fillable = [
        'movie_id',
        'hall_id',
        'start_time',
        'end_time',
    ];*/

    protected $table = 'sessionss';

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }

    public function scopeDate($query, $date) {
        return $query->whereDate('start_time', $date);
    }

    public function scopeMovie($query, $movie) {
        return $query->where('movie_id', $movie);
    }
}
