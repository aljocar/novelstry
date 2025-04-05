@extends('layouts.app')

@section('title', $novel->title . ' | Novelas _try')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">

        <!-- Parte izquierda (5 columnas en pantallas grandes, 12 en móviles) -->
        <div class="col-md-5 col-12 mb-4">
            <img src="{{ asset('storage/' . $novel->cover_image) }}"
                class="img-fluid shadow mb-4 w-50" 
                alt="Portada de {{ $novel->title }}"
            >
    
            <h2>{{ $novel->title }}</h2>
            <p>
                by 
                <a href="{{ route('profiles.show', $novel->user->username) }}" class="text-black">
                    <b>{{ $novel->user->username }}</b>
                </a>
            </p>
    
            @auth
                @if ($novel->user_id == auth()->id() || auth()->user()->user_type == 2)
                    <div class="d-grid gap-2 w-50">
                        <a href="{{ route('chapters.create', $novel) }}" class="btn btn-light d-block">
                            + Capitulo Nuevo
                        </a>
    
                        <a href="{{ route('novels.edit', $novel) }}" class="btn btn-secondary d-block">
                            Editar Novela
                        </a>
    
                        <a href="{{ route('novels.delete', $novel) }}" class="btn btn-danger d-block">
                            Eliminar Novela
                        </a>
                    </div>
                @endif
            @endauth
        </div>
    
        <!-- Parte derecha (7 columnas en pantallas grandes, 12 en móviles) -->
        <div class="col-md-7 col-12">
            <h5>Sinopsis</h5>
            <p>
                {{ $novel->synopsis }}
            </p>
        </div>
    </div>

    <!-- Separación vertical -->
    <hr class="my-4"> <!-- Línea horizontal con margen arriba y abajo -->

    <div class="row">
        <div class="col-6">

            <a href="{{ route('chapters.index', $novel) }}" class="btn p-0" style="text-align: left;">
                <div class="card text-muted">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <!-- Contenido del card-body -->
                        <div>
                            <h2>Capítulos</h2>
                            <p>
                                @if ($latestChapter)
                                    <div class="mb-2">
                                        {{ $latestChapter->title }}
                                    </div>
                                    <div>
                                        Última Actualización: {{ $latestChapter->created_at->format('d/m/Y') }}
                                    </div>
                                @else
                                    No hay capítulos disponibles.
                                @endif
                            </p>
                        </div>

                        <!-- Ícono de Bootstrap Icons -->
                        <div class="d-flex align-items-center" style="font-size: 40px; margin-left: 16px;">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </a>

        </div>
    </div>

@endsection
