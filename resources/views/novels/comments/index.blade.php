@extends('layouts.app')

@section('title', $novel->title . ' - Comentarios | Novelas _try')

@section('content')

    <a href="{{ route('novels.show', $novel) }}" class="btn text-muted">
        <h1 class="text-2xl mb-3">{{ $novel->title }}</h1>
    </a>

    <div>
        <a href="{{ route('novels.show', $novel) }}" class="btn btn-secondary mb-4">Volver</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">

            <!-- Formulario para agregar comentarios -->
            @auth
                <form action="{{ route('comments.store', ['commentableType' => 'novel', 'commentableId' => $novel->id]) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="form-group">
                        <textarea name="content" rows="3" class="form-control" placeholder="Escribe tu comentario..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Publicar comentario</button>
                </form>
            @else
                <div class="alert alert-info">
                    <p class="mb-0">Inicia sesión para dejar un comentario.</p>
                </div>
            @endauth

            <!-- Mostrar comentarios -->
            @foreach ($comments as $comment)
                <div class="card mb-3">
                    <div class="card-body">
                        <!-- Contenido del comentario -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1">
                                    <a href="{{ route('profiles.show', $novel->user->username) }}" class="text-black">
                                        <img src="{{ $comment->user->profile_image }}"
                                            class="img-fluid shadow" width="30px" alt="Imagen de Perfil">
                                        <strong>{{ $comment->user->username }}</strong>
                                    </a>
                                </p>
                                <p class="mb-0">{{ $comment->content }}</p>
                            </div>
                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>

                        <!-- Botón para eliminar comentario (solo para dueños o administradores) -->
                        @auth
                            <!-- Botones de acciones (Editar y Eliminar) -->
                            @if (auth()->id() === $comment->user_id || auth()->user()->user_type === 2)
                                <div class="mt-2">
                                    <!-- Botón para editar comentario -->
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
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
                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este comentario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </div>
                            @endif
                        @endauth

                    </div>
                </div>
            @endforeach
            <!-- Button trigger modal -->




            <!-- Mostrar enlaces de paginación -->
            <div class="d-flex justify-content-center">
                {{ $comments->links() }}
            </div>

        </div>
    </div>

@endsection
