<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Novel;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        // Obtener todos los comentarios, ordenados por fecha de creación (de más nuevo a más antiguo)
        $comments = Comment::orderBy('created_at', 'desc')
            ->with(['user', 'commentable']) // Cargar la relación 'user' y 'commentable'
            ->get(); // Paginar en grupos de 10

        return view('admin.comments.index', compact('comments'));
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
