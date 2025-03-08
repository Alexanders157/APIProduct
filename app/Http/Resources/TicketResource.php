<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


    private $message;


    public function __construct($resource, $message)
    {
        parent::__construct($resource);
        $this->message = $message;
    }


    public function toArray(Request $request): array
    {
        return [
            'movie' => $this->session->movie->title,
            'seat_number' => $this->seat_number,
            'price' => $this->price,
            'purchase_date' => $this->purchase_date,
            'message' => $this->message
        ];
    }
}
