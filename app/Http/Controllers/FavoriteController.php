<?php

namespace App\Http\Controllers;

use App\Models\Novel;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function myFavorites()
    {
        $favorites = auth()->user()->favorites;
        return view('favorites.index', compact('favorites'));
    }

    public function store(Novel $novel)
    {
        auth()->user()->favorites()->attach($novel->id);

        return back()->with('success', 'Novela añadida a favoritos');
    }

    public function destroy(Novel $novel)
    {
        auth()->user()->favorites()->detach($novel->id);
        return back()->with('success', 'Novela eliminada de favoritos');
    }
}
