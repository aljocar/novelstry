@extends('layouts.app')

@section('title', $chapter->title . ' - ' . $novel->title . ' | Novelas _try')

@section('content')
    <a href="{{ route('chapters.index', $novel) }}" class="mb-3 btn btn-secondary">Volver</a>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <div class="row">
        <!-- Parte izquierda (5 columnas) -->
        <div class="">

            <h2 class="mb-3">{{ $chapter->title }}</h2>

            @auth
                @if ($novel->user_id == auth()->id() || auth()->user()->user_type == 2)
                    <a href="{{ route('chapters.edit', [$novel, $chapter]) }}" class="btn btn-secondary">
                        Editar Capitulo
                    </a>

                    <a href="{{ route('chapters.delete', [$novel, $chapter]) }}" class="btn btn-danger">
                        Eliminar Capitulo
                    </a>
                @endif
            @endauth
        </div>

    </div>

    <!-- Separación vertical -->
    <hr class="my-4"> <!-- Línea horizontal con margen arriba y abajo -->

    <div class="row">
        <div class="col-12">

            <div class="card border-0">
                <div class="card-body">

                    <p style="white-space: pre-line;">
                        {{ $chapter->content }}
                    </p>

                </div>
            </div>

        </div>
    </div>

    <!-- Separación vertical -->
    <hr class="my-4">

    <!-- Botones de navegación -->
    <div class="row">
        <div class="col-6">
            @if ($previousChapter)
                <a href="{{ route('chapters.show', [$novel, $previousChapter]) }}" class="btn btn-secondary">
                    &larr; Capítulo Anterior
                </a>
            @else
                <button class="btn btn-secondary" disabled>&larr; Capítulo Anterior</button>
            @endif
        </div>
        <div class="col-6 text-end">
            @if ($nextChapter)
                <a href="{{ route('chapters.show', [$novel, $nextChapter]) }}" class="btn btn-secondary">
                    Capítulo Siguiente &rarr;
                </a>
            @else
                <button class="btn btn-secondary" disabled>Capítulo Siguiente &rarr;</button>
            @endif
        </div>
    </div>

    <hr class="my-4"> <!-- Línea horizontal con margen arriba y abajo -->

    <div class="row">
        <div class="col-12">
            <!-- Formulario para agregar comentarios -->
            @auth
                <form action="{{ route('comments.store', ['commentableType' => 'chapter', 'commentableId' => $chapter->id]) }}"
                    method="POST" class="mb-4">
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
                                    <a href="{{ route('profiles.show', $comment->user->username) }}" class="text-black">
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
                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('¿Estás seguro de que deseas eliminar este comentario?');">
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

            <!-- Mostrar enlaces de paginación -->
            <div class="d-flex justify-content-center">
                {{ $comments->links() }}
            </div>
        </div>
    </div>
@endsection
