<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Obtener novelas con su último capítulo (usando la relación latestChapter)
        $novels = Novel::with('latestChapter')
            ->has('chapters') // Solo novelas con al menos un capítulo
            ->orderByDesc(
                Chapter::select('created_at')
                    ->whereColumn('novel_id', 'novels.id')
                    ->latest()
                    ->limit(1)
            )
            ->paginate(10);

        // Top 5 novelas con más visitas
        $topNovels = Novel::withCount('visits')
            ->orderBy('visits_count', 'desc')
            ->take(5)
            ->get();

        return view('home', compact('novels', 'topNovels'));
    }
}
