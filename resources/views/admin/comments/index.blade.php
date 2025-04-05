@extends('layouts.admin')

@section('title', 'Comentarios | Novelas _Try')

@section('typeAdmin', 'Comentarios')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container">
    
        <table id="mitable" class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Contenido</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Título</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($comments as $comment)
                    <tr>
                        <td>{{ $comment->id }}</td>
                        <td>{{ $comment->content }}</td>
                        <td>{{ $comment->user->username }}</td>
                        <td>
                            @if ($comment->commentable_type === 'App\Models\Novel')
                                Novela
                            @elseif ($comment->commentable_type === 'App\Models\Chapter')
                                Capítulo
                            @endif
                        </td>
                        <td>
                            @if ($comment->commentable_type === 'App\Models\Novel')
                                {{ $comment->commentable->title }}
                            @elseif ($comment->commentable_type === 'App\Models\Chapter')
                                {{ $comment->commentable->title }} - {{ $comment->commentable->novel->title }}
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <!-- Botón para editar comentario -->
                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                    data-bs-target="#editCommentModal{{ $comment->id }}">
                                    Editar
                                </button>
    
                                <!-- Modal -->
                                <div class="modal fade" id="editCommentModal{{ $comment->id }}" tabindex="-1"
                                    aria-labelledby="editCommentModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title fs-5" id="editCommentModalLabel">Editar comentario
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('comments.update', $comment) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <textarea name="content" rows="3" class="form-control">{{ $comment->content }}</textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary">Guardar
                                                            Cambios</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
    
                                <!-- Botón para eliminar comentario -->
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('¿Estás seguro de que deseas eliminar este comentario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>

@endsection
