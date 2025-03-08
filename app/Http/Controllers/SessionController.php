<?php

namespace App\Http\Controllers;

use App\Http\Resources\SessionResource;
use App\Models\Session;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sessions = Session::with(['movie', 'hall'])
            ->when($request->movie_id, fn($query) => $query->where('movie_id', $request->movie_id))
            ->when($request->date, fn($query) => $query->whereDate('start_time', Carbon::parse($request->date)))
            ->orderBy('start_time')
            ->get();

        return SessionResource::collection($sessions);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $session = Cache::remember('session_{$id}', 600, function () use ($id) {
            return Session::with('hall', 'movie', 'tickets')->findOrFail($id);
        });

        return new SessionResource($session);
    }
}
