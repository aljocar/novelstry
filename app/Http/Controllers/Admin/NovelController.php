<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Http\Request;

class NovelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexNovel()
    {
        $novels = Novel::orderBy('id', 'desc')
            ->withCount('chapters')
            ->get(); // Ejecuta la consulta y obtén los resultados

        $novels->load(['visits', 'favoritedBy']);

        return view('admin.novel.index', compact('novels'));
    }

    public function indexChapter()
    {
        $chapters = Chapter::with('novel')->get(); // Carga la relación "novel" para cada capítulo

        return view('admin.chapter.index', compact('chapters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
