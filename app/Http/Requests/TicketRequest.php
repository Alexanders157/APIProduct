<?php

namespace App\Http\Requests;

use App\Models\Session;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $session = Session::findOrFail($this->session_id);
        return [
            'user_id' => 'required|exists:users,id',
            'session_id' => 'required|exists:sessionss,id',
            'seat_number' => [
                'required',
                Rule::unique('tickets')->where(function ($query) {
                    return $query->where('session_id', $this->session_id);
                }),
            ],
        ];
    }
}
