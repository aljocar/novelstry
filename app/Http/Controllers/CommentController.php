<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Novel;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Novel $novel)
    {
        // Obtener los comentarios de la novela, ordenados por fecha de creación (de más nuevo a más antiguo) y paginados
        $comments = $novel->comments()
            ->with('user') // Cargar la relación 'user'
            ->orderBy('created_at', 'desc') // Ordenar de más nuevo a más antiguo
            ->paginate(10); // Paginar en grupos de 10

        return view('novels.comments.index', compact('novel', 'comments'));
    }

    // Almacenar un comentario
    public function store(Request $request, $commentableType, $commentableId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // Determinar si el comentario es para una novela o un capítulo
        if ($commentableType === 'novel') {
            $commentable = Novel::findOrFail($commentableId);
        } elseif ($commentableType === 'chapter') {
            $commentable = Chapter::findOrFail($commentableId);
        } else {
            return redirect()->back()->with('error', 'Tipo de comentario no válido.');
        }

        // Crear el comentario usando la relación polimórfica
        $commentable->comments()->create([
            'content' => $request->input('content'),
            'user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Comentario publicado.');
    }

    public function update(Request $request, Comment $comment)
    {
        // Validar el contenido del comentario
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // Verificar si el usuario tiene permiso para editar el comentario
        if (auth()->id() !== $comment->user_id && auth()->user()->user_type !== 2) {
            return redirect()->back()->with('error', 'No tienes permiso para editar este comentario.');
        }

        // Actualizar el contenido del comentario
        $comment->content = $request->input('content');
        $comment->save();

        return redirect()->back()->with('success', 'Comentario actualizado correctamente.');
    }

    // Eliminar un comentario
    public function destroy(Comment $comment)
    {
        // Verificar si el usuario es el dueño del comentario o un administrador
        if (auth()->id() === $comment->user_id || auth()->user()->user_type === 2) {
            $comment->delete();
            return redirect()->back()->with('success', 'Comentario eliminado.');
        }

        return redirect()->back()->with('error', 'No tienes permiso para eliminar este comentario.');
    }
}
