<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Obtener las novelas que tienen al menos un capítulo, ordenadas por la fecha del último capítulo
        $novels = Novel::with(['latestChapter'])
            ->has('chapters')
            ->addSelect([
                'latest_chapter_date' => Chapter::select('created_at')
                    ->whereColumn('novel_id', 'novels.id')
                    ->latest()
                    ->take(1)
            ])
            ->orderByDesc('latest_chapter_date')
            ->paginate(10);

        // Top 5 novelas con más visitas
        $topNovels = Novel::withCount('visits')
            ->orderBy('visits_count', 'desc')
            ->take(5)
            ->get();

        return view('home', compact('novels', 'topNovels'));
    }
}
