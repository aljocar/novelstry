<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Novel;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Services\ImgurService;

class NovelController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el término de búsqueda
        $search = $request->input('search');

        // Consulta base para las novelas
        $query = Novel::orderBy('id', 'desc');

        // Si hay un término de búsqueda, aplicar filtros
        if ($search) {
            $query->where(function ($q) use ($search) {
                // Buscar por título de la novela
                $q->where('title', 'like', "%{$search}%")
                    // Buscar por nombre de usuario
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('username', 'like', "%{$search}%");
                    })
                    // Buscar por categorías
                    ->orWhereHas('categories', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Paginar los resultados
        $novels = $query->paginate(12);

        // Pasar los resultados a la vista
        return view('novels.index', compact('novels', 'search'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('novels.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validación
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'min:4', 'max:255'],
            'synopsis' => 'required',
            'categories' => 'required|array', // Validar que se hayan seleccionado categorías
            'categories.*' => 'exists:categories,id', // Validar que las categorías existan en la base de datos
            'cropped_image' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^data:image\/(\w+);base64,/', $value, $matches)) {
                        $fail('El archivo no es una imagen válida.');
                        return;
                    }

                    $imageType = $matches[1];

                    if (!in_array($imageType, ['jpeg', 'png', 'jpg', 'svg'])) {
                        $fail('El tipo de imagen no es soportado. Use JPEG, PNG, JPG o SVG.');
                        return;
                    }

                    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $value));
                    $imageSize = strlen($imageData);

                    if ($imageSize > 2097152) {
                        $fail('La imagen no debe pesar más de 2 MB.');
                    }
                },
            ],
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.min' => 'El título debe tener al menos 5 caracteres.',
            'synopsis.required' => 'La sinopsis es obligatoria.',
            'categories.required' => 'Debes seleccionar al menos una categoría.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Verificar si el usuario ya tiene 15 novelas
        if ($user->novels()->count() >= 15) {
            return redirect()->route('novels.index')
                ->with('error', 'No puedes tener más de 15 novelas al mismo tiempo.');
        }

        // Manejo de la imagen
        $coverUrl = asset('https://i.imgur.com/OqeisHs.jpg'); // URL por defecto

        if ($request->cropped_image) {
            $imgurService = app(ImgurService::class);
            $coverUrl = $imgurService->uploadBase64Image($request->cropped_image) ?? $coverUrl;
        }

        // Crear novela
        $novel = Novel::create([
            'title' => $request->title,
            'synopsis' => $request->synopsis,
            'user_id' => Auth::id(),
            'cover_image' => $coverUrl,
        ]);

        $novel->categories()->attach($request->categories);

        return redirect()->route('novels.index', $novel)
            ->with([
                'success' => true,
                'titulo' => $novel->title,
                'cover' => $novel->cover_image,
                'novel_id' => $novel->id
            ]);
    }

    public function show(Request $request, Novel $novel)
    {
        $ip = $request->ip();
        $userId = Auth::id();

        // Verificar si ya existe una visita reciente del mismo usuario o IP
        $recentVisit = Visit::where('novel_id', $novel->id)
            ->where(function ($query) use ($userId, $ip) {
                $query->where('user_id', $userId)
                    ->orWhere('ip_address', $ip);
            })
            ->where('created_at', '>=', now()->subHours(1)) // Evitar duplicados en las últimas 24 horas
            ->first();

        if (!$recentVisit) {
            // Registrar la visita
            $visit = new Visit();
            $visit->novel_id = $novel->id;
            $visit->user_id = $userId;
            $visit->ip_address = $ip;
            $visit->save();
        }

        // Cargar la relación 'visits' y 'favoritedBy' antes de pasar la novela a la vista
        $novel->load(['visits', 'favoritedBy']);

        // Obtener el capítulo más reciente (último creado)
        $latestChapter = $novel->chapters()->latest()->first();

        // Obtener el último comentario de la novela
        $latestComment = $novel->comments()->latest()->first();

        // Pasar la novela, los capítulos paginados y el orden a la vista
        return view('novels.show', compact('novel', 'latestChapter', 'latestComment'));
    }

    public function edit(Novel $novel, Request $request)
    {
        if ($novel->user_id !== auth()->id() && auth()->user()->user_type != 2) {
            return redirect()->route('novels.index')->with('errorAdmin', 'No tienes permiso para editar esta novela.');
        }

        $fromTable = $request->query('from_table', false); // Captura el parámetro from_table
        $categories = Category::all(); // Obtener todas las categorías

        return view('novels.edit', compact('novel', 'fromTable', 'categories'));
    }

    public function update(Request $request, Novel $novel, ImgurService $imgurService)
    {
        // Verificar si el usuario autenticado es el creador de la novela o un administrador
        if ($novel->user_id !== auth()->id() && auth()->user()->user_type != 2) {
            abort(403, 'No tienes permiso para editar esta novela.');
        }

        // Validación
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'min:4', 'max:255'],
            'synopsis' => 'required',
            'categories' => 'required|array', // Validar que se hayan seleccionado categorías
            'categories.*' => 'exists:categories,id', // Validar que las categorías existan en la base de datos
            'cropped_image' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^data:image\/(\w+);base64,/', $value, $matches)) {
                        $fail('El archivo no es una imagen válida.');
                        return;
                    }

                    $imageType = $matches[1];

                    if (!in_array($imageType, ['jpeg', 'png', 'jpg', 'svg'])) {
                        $fail('El tipo de imagen no es soportado. Use JPEG, PNG, JPG o SVG.');
                        return;
                    }

                    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $value));
                    $imageSize = strlen($imageData);

                    if ($imageSize > 2097152) {
                        $fail('La imagen no debe pesar más de 2 MB.');
                    }
                },
            ],
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.min' => 'El título debe tener al menos 5 caracteres.',
            'synopsis.required' => 'La sinopsis es obligatoria.',
            'categories.required' => 'Debes seleccionar al menos una categoría.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Manejar la imagen de portada
        if ($request->cropped_image) {
            // Subir la nueva imagen a Imgur
            $imgurUrl = $imgurService->uploadBase64Image($request->cropped_image);

            if ($imgurUrl) {
                // Actualizar con la nueva URL de Imgur
                $novel->cover_image = $imgurUrl;
            } else {
                // Si falla la subida, mantener la imagen actual
                return redirect()->back()
                    ->with('error', 'No se pudo actualizar la imagen de portada. Inténtalo nuevamente.')
                    ->withInput();
            }
        }

        // Actualizar los demás campos
        $novel->update([
            'title' => $request->title,
            'synopsis' => $request->synopsis,
            // cover_image ya se actualizó arriba si hubo cambio
        ]);

        // Sincronizar las categorías seleccionadas
        $novel->categories()->sync($request->categories);

        return redirect()->route('novels.show', $novel)
            ->with('success', 'Se ha actualizado con éxito');
    }

    public function delete(Novel $novel, Request $request)
    {
        if ($novel->user_id !== auth()->id() && auth()->user()->user_type != 2) {
            return redirect()->route('novels.index')->with('errorAdmin', 'No tienes permiso para eliminar esta novela.');
        }

        $fromTable = $request->query('from_table', false); // Captura el parámetro from_table

        return view('novels.delete', compact('novel', 'fromTable'));
    }

    public function destroy(Novel $novel)
    {
        // Verificar si el usuario autenticado es el creador de la novela o un administrador
        if ($novel->user_id !== auth()->id() && auth()->user()->user_type != 2) {
            abort(403, 'No tienes permiso para eliminar esta novela.');
        }

        // Validar que el campo de confirmación tenga el valor correcto
        if (request('confirmDelete') !== 'Eliminar') {
            return redirect()->back()->withErrors(['confirmDelete' => 'Debes escribir "Eliminar" para confirmar.']);
        }

        $novel->delete();

        return redirect()->route('novels.index');
    }
}
