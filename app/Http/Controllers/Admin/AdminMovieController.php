<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MovieStoreRequest;
use App\Http\Requests\MovieUpdateRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminMovieController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(MovieStoreRequest $request)
    {
        $validatedData = $request->validated();
        $movie = Movie::create($validatedData);

        Cache::forget("movies");

        return new MovieResource($movie);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MovieUpdateRequest $request, string $id)
    {
        $movie = Movie::find($id);

        $validatedData = $request->validated();

        $movie->update($validatedData);

        Cache::forget("movies");
        Cache::forget("movie_{$id}");

        return new MovieResource($movie);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json(['message' => 'Фильм не найден'], 404);
        }

        $movie->delete();

        Cache::forget('movies');
        Cache::forget("movie_{$id}");

        return new MovieResource($movie);
    }
}
