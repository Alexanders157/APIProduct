<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    public function toArray($request)
    {
        $occupiedSeats = $this->tickets()->pluck('seat_number');
        $allSeats = range(1, $this->hall->capacity);
        $availableSeats = array_diff($allSeats, $occupiedSeats->toArray());

        return [
            'id' => $this->id,
            'movie_title' => $this->movie->title,
            'start_time' => $this->start_time,
            'hall_name' => $this->hall->name,
            'capacity' => $this->hall->capacity,
            'available_seats' => array_values($availableSeats),
            'occupied_seats' => $occupiedSeats->toArray(),
        ];
    }
}
