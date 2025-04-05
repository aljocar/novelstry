<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Novel;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * @method static \Barryvdh\DomPDF\PDF loadHTML(string $html)
 */


class StatisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalUsers = User::count();
        $totalNovels = Novel::count();

        // Top 5 usuarios con más novelas
        $novelsPerUser = User::withCount('novels')
            ->orderBy('novels_count', 'desc')
            ->take(5)
            ->get();

        // Top 5 usuarios con más comentarios
        $commentsPerUser = User::withCount('comments')
            ->orderBy('comments_count', 'desc')
            ->take(5)
            ->get();

        // Top 5 novelas con más visitas
        $visitsPerNovel = Novel::withCount('visits')
            ->orderBy('visits_count', 'desc')
            ->take(5)
            ->get();

        // Top 5 novelas con más favoritos
        $favoritesPerNovel = Novel::withCount('favoritedBy')
            ->orderBy('favorited_by_count', 'desc')
            ->take(5)
            ->get();

        // Top 5 novelas con más capítulos
        $chaptersPerNovel = Novel::withCount('chapters')
            ->orderBy('chapters_count', 'desc')
            ->take(5)
            ->get();

        // Distribución de novelas por categoría
        $novelsPerCategory = Category::withCount('novels')
            ->orderBy('novels_count', 'desc')
            ->get();

        // Porcentaje de visitas de usuarios registrados vs. no registrados
        $totalVisits = Visit::count();
        $registeredVisits = Visit::whereNotNull('user_id')->count();
        $unregisteredVisits = Visit::whereNull('user_id')->count();

        return view('admin.statistics.index', compact(
            'totalUsers',
            'totalNovels',
            'novelsPerUser',
            'commentsPerUser',
            'visitsPerNovel',
            'favoritesPerNovel',
            'chaptersPerNovel',
            'novelsPerCategory',
            'totalVisits',
            'registeredVisits',
            'unregisteredVisits'
        ));
    }

    public function generatePdf(Request $request)
    {
        // Obtener las imágenes de las gráficas
        $images = json_decode($request->input('images'), true);

        if (empty($images)) {
            return response()->json(['error' => 'No se encontraron imágenes.'], 400);
        }

        // Crear el contenido HTML del PDF
        $html = '
            <!DOCTYPE html>
            <html>
            <head>
                <title>Reporte de Gráficas</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    h1 { text-align: center; color: #333; }
                    img { max-width: 100%; height: auto; margin-bottom: 20px; display: block; margin-left: auto; margin-right: auto; }
                    .graph-container { page-break-inside: avoid; margin-bottom: 30px; }
                    .graph-title { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 10px; }
                </style>
            </head>
            <body>
                <h1>Reporte de Gráficas</h1>'
        ;

        // Incluir las imágenes en el HTML
        foreach ($images as $image) {
            if (is_array($image) && isset($image['imgURI'])) {
                $html .= '<img src="' . $image['imgURI'] . '" style="max-width: 80%; height: auto; margin-bottom: 20px;">';
            } else {
                $html .= '<p>Error al cargar la imagen.</p>';
            }
        }

        // Generar el PDF
        $pdf = Pdf::loadHTML($html);

        // Abrir el PDF en una nueva pestaña
        return $pdf->stream('graficas.pdf');
    }
}
