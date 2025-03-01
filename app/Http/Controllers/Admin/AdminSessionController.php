<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSessionRequest;
use App\Http\Resources\SessionResource;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminSessionController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSessionRequest $request)
    {
        $validatedData = $request->validated();

        $isHallBusy = Session::where('hall_id', $request->hall_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [
                    $request->start_time,
                    Carbon::parse($request->start_time)->addMinutes(30)
                ])
                    ->orWhereBetween('end_time', [
                        $request->start_time,
                        Carbon::parse($request->start_time)->addMinutes(30)
                    ]);
            })->exists();

        if ($isHallBusy) {
            return response()->json([
                'error' => 'Зал занят в это время'
            ], 409);
        }

        $session = Session::create($validatedData);

        return new SessionResource($session);
    }
}
