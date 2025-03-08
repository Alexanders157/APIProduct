<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = Cache::remember("movies", 3600, function () {
            return Movie::all();
        });
        return MovieResource::collection($movies);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $movie = Cache::remember("movie_{$id}", 3600, function () use ($id) {
            return Movie::find($id);
        });

        if (!$movie) {
            return response()->json(['error' => 'Фильм не найден'], 404);
        }

        return new MovieResource($movie);
    }

}
