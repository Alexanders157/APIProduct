<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Session;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ticket = Ticket::all();

        return TicketResource::collection($ticket);
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

        $ticket = Ticket::create($validatedData);

        return new TicketResource($ticket, 'Билет на фильм успешно приобретён', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ticket = Ticket::find($id);
        $ticket->load('movie');

        return new TicketResource($ticket, 'Ваш купленный билет', 200);
    }

}
