<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Session;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Cache::remember("tickets", 3600, function () {
            return Ticket::all();
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

        return new TicketResource($ticket, 'Билет на фильм успешно приобретён', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ticket = Cache::remember("ticket_{$id}", 3600, function () use ($id) {
            $ticket = Ticket::find($id);
            $ticket->load('movie');
            return $ticket;
        });

        return new TicketResource($ticket, 'Ваш купленный билет', 200);
    }

}
