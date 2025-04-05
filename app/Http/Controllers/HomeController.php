<?php

namespace App\Http\Controllers;

use App\Models\Novel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Obtener las novelas que tienen al menos un capítulo, ordenadas por la fecha del último capítulo
        $novels = Novel::with(['chapters' => function ($query) {
            $query->latest()->limit(10); // Cargar todos los capítulos, ordenados por el más reciente
        }])
            ->has('chapters') // Solo novelas con al menos un capítulo
            ->join('chapters', 'novels.id', '=', 'chapters.novel_id') // Unir con la tabla chapters
            ->orderBy('chapters.created_at', 'desc') // Ordenar por la fecha del último capítulo
            ->select('novels.*') // Seleccionar solo las columnas de la tabla novels
            ->distinct() // Evitar duplicados
            ->paginate(10); // Paginar los resultados (10 por página)

        // Top 5 novelas con más visitas
        $topNovels = Novel::withCount('visits')
            ->orderBy('visits_count', 'desc')
            ->take(5)
            ->get();

        return view('home', compact('novels', 'topNovels'));
    }
}
