<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHallRequest;
use App\Http\Resources\HallResource;
use App\Models\Hall;
use Illuminate\Http\Request;

class AdminHallController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHallRequest $request)
    {
        $validatedData = $request->validated();

        $hall = Hall::create($validatedData);

        return new HallResource($hall);
    }
}
