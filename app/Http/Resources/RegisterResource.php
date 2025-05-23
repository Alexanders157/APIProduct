<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterResource extends JsonResource
{
    private $token;
    private $message;

    public function __construct($resource, $token, $message) {
        parent::__construct($resource);
        $this->token = $token;
        $this->message = $message;
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'token' => $this->token,
            'message' => $this->message
        ];
    }
}
