<?php

namespace App\Http\Controllers;

use App\Http\Resources\SessionResource;
use App\Models\Session;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'movie_id' => 'sometimes|integer|exists:movies,id',
            'date' => 'sometimes|date_format:Y-m-d',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ]);

        $query = Session::with(['movie', 'hall'])
        ->orderBy('start_time', 'asc');

        if ($request->has('movie_id')) {
            $query->where('movie_id', $request->movie_id);
        }

        if ($request->has('date')) {
            $targetDate = Carbon::parse($request->date);
            $query->whereDate('start_time', $targetDate);
        }

        $perPage = $request->per_page ?? 15;
        $sessions = $query->paginate($perPage);

        $sessions->getCollection()->transform(function ($session) {
            return [
                'id' => $session->id,
                'start_time' => $session->start_time->format('Y-m-d H:i'),
                'end_time' => $session->end_time->format('Y-m-d H:i'),
                'movie' => $session->movie->title,
                'hall' => $session->hall->name,
                'available_seats' => $session->hall->capacity - $session->tickets_count,
                'price' => $session->price,
            ];
        });

        return response()->json($sessions);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $session = Session::with('hall', 'movie', 'tickets')->findOrFail($id);

        return new SessionResource($session);
    }
}
