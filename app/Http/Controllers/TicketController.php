<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Http\Resources\TicketResource;
use App\Jobs\SendTicketConfirmation;
use App\Models\Session;
use App\Models\Ticket;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Authenticatable $user)
    {
        $tickets = Cache::remember("tickets_user_{$user->id}", 3600, function () use ($user) {
            return Ticket::where('user_id', $user->id)->get();
        });

        return TicketResource::collection($tickets);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TicketRequest $request)
    {
        $validatedData = $request->validated();

        $session = Session::findOrFail($validatedData['session_id']);

        $price = $session->price;

        $validatedData['price'] = $price;
        $validatedData['purchase_date'] = now();

        $ticket = Ticket::create($validatedData)->load('session.movie');

        Cache::forget("session_{$validatedData['session_id']}");
        Cache::forget("tickets");

        SendTicketConfirmation::dispatch($ticket);

        return new TicketResource($ticket, 'Билет на фильм успешно приобретён', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Authenticatable $user, $ticket)
    {
        $ticketData = Cache::remember("ticket_{$ticket}_user_{$user->id}", 3600, function () use ($user, $ticket) {
            $ticketModel = Ticket::where('id', $ticket)
                ->where('user_id', $user->id)
                ->firstOrFail();
            $ticketModel->load('session.movie');
            return $ticketModel;
        });

        return new TicketResource($ticketData, 'Ваш купленный билет', 200);
    }

}
