<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movie = Movie::all();
        return MovieResource::collection($movie);
    }
    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        //
    }

}
