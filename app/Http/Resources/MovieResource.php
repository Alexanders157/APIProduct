<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        'id' => $this->id,
        'title' => $this->title,
        'description' => $this->description,
        'duration' => $this->duration,
        'genre' => $this->genre,
        'release_date' => $this->release_date
        ];
    }
}
