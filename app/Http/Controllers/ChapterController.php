<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Novel $novel)
    {
        // Obtener el orden de la solicitud (asc o desc)
        $order = request()->query('order', 'asc'); // Por defecto es 'desc'

        // Filtrar los capítulos por novela y ordenarlos
        $chapters = $novel->chapters()->orderBy('id', $order)->paginate(24);

        // Incluir el parámetro 'order' en los enlaces de paginación
        $chapters->appends(['order' => $order]);

        // Pasar la novela y los capítulos a la vista
        return view('chapters.index', compact('novel', 'chapters', 'order'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Novel $novel)
    {
        return view('chapters.create', compact('novel'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Novel $novel)
    {
        //Validacion
        $request->validate([
            'title' => ['required', 'min:3', 'max:255'],
            'content' => ['required', 'min:10']
        ], [
            //Mensaje de Error
            'title.required' => 'El título es obligatorio.',
            'title.min' => 'El título debe tener al menos 3 caracteres.',
            'content.required' => 'El contenido es obligatoria.',
            'content.min' => 'El contenido debe tener al menos 10 caracteres.',
        ], [
            //Atributos
        ]);

        Chapter::create([
            'title' => $request->title,
            'content' => $request->content,
            'novel_id' => $novel->id,
        ]);

        // Mensaje de éxito con HTML
        return redirect()->route('novels.show', $novel)
            ->with('success', 'Capitulo Creado');
    }

    /**
     * Display the specified resource.
     */
    public function show(Novel $novel, Chapter $chapter)
    {
        // Obtener el capítulo anterior
        $previousChapter = Chapter::where('novel_id', $novel->id)
            ->where('id', '<', $chapter->id)
            ->orderBy('id', 'desc')
            ->first();

        // Obtener el siguiente capítulo
        $nextChapter = Chapter::where('novel_id', $novel->id)
            ->where('id', '>', $chapter->id)
            ->orderBy('id', 'asc')
            ->first();

        // Obtener los comentarios del capítulo, ordenados por fecha de creación (de más nuevo a más antiguo) y paginados
        $comments = $chapter->comments()
            ->with('user') // Cargar la relación 'user'
            ->orderBy('created_at', 'desc') // Ordenar de más nuevo a más antiguo
            ->paginate(10); // Paginar en grupos de 10

        return view('chapters.show', compact('novel', 'chapter', 'previousChapter', 'nextChapter', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Novel $novel, Chapter $chapter, Request $request)
    {
        if ($novel->user_id !== auth()->id() && auth()->user()->user_type != 2) {
            return redirect()->route('novels.index')->with('errorAdmin', 'No tienes permiso para editar esta novela.');
        }

        $fromTable = $request->query('from_table', false); // Captura el parámetro from_table

        return view('chapters.edit', compact('novel', 'chapter', 'fromTable'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Novel $novel, Chapter $chapter)
    {
        // Verificar si el usuario autenticado es el creador de la novela o un administrador
        if ($novel->user_id !== auth()->id() && auth()->user()->user_type != 2) {
            abort(403, 'No tienes permiso para editar este capitulo.');
        }

        $request->validate([
            'title' => ['required', 'min:3', 'max:255'],
            'content' => ['required', 'min:10']
        ], [
            //Mensaje de Error
            'title.required' => 'El título es obligatorio.',
            'title.min' => 'El título debe tener al menos 3 caracteres.',
            'content.required' => 'El contenido es obligatoria.',
            'content.min' => 'El contenido debe tener al menos 10 caracteres.',
        ], [
            //Atributos
        ]);

        $chapter->update($request->all());

        return redirect()->route('chapters.show', [$novel, $chapter])
            ->with('success', 'Se ha actualizado con exito');
    }

    public function delete(Novel $novel, Chapter $chapter, Request $request)
    {
        if ($novel->user_id !== auth()->id() && auth()->user()->user_type != 2) {
            return redirect()->route('novels.index')->with('errorAdmin', 'No tienes permiso para eliminar esta novela.');
        }

        $fromTable = $request->query('from_table', false); // Captura el parámetro from_table

        return view('chapters.delete', compact('novel', 'chapter', 'fromTable'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Novel $novel, Chapter $chapter)
    {
        // Verificar si el usuario autenticado es el creador de la novela o un administrador
        if ($novel->user_id !== auth()->id() && auth()->user()->user_type != 2) {
            abort(403, 'No tienes permiso para eliminar este capitulo.');
        }

        $chapter->delete();

        return redirect()->route('novels.show', compact('novel'))->with('success', 'Se ha eliminado con exito');
    }
}
