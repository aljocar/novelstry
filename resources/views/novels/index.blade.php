@extends('layouts.app')

@section('title', 'Novelas | Novelas _try')

{{-- @section('title', 'Página de Inicio') --}}

@section('content')
    {{-- Modal de éxito --}}
    @if (session('success'))
        <div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="successModalLabel">
                            La novela fue creada
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <h3>{{ session('titulo') }}</h3>
                    </div>

                    @php
                        $cover = session('cover');
                    @endphp

                    <img src="{{ $cover }}" class="img-fluid shadow mb-3 ms-3" style="max-width: 50%;" width="625"
                        height="1000">

                    <div class="modal-footer">
                        <a href="{{ route('novels.show', session('novel_id')) }}" class="btn btn-primary">Ver novela</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <h1 class="text-2xl">Novelas</h1>

    <div class="container">

        <!-- Formulario de búsqueda -->
        <form action="{{ route('novels.index') }}" method="GET" class="mb-4 col-12 col-md-6">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                    placeholder="Buscar por nombre, usuario o categoría..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>

        @if ($novels->isEmpty())
            <div class="card-text text-muted mb-4">
                No se encontraron resultados para "{{ $search }}".
            </div>
        @endif

        <div class="row">
            @foreach ($novels as $novel)
                <div class="col-6 col-md-3 col-sm-6 col-xm-6 mb-4">
                    <!-- Cambiado a col-md-3 para 4 columnas en pantallas grandes -->
                    <a href="{{ route('novels.show', $novel) }}" class="text-decoration-none text-dark">
                        <!-- Enlace que cubre toda la tarjeta -->
                        <div class="card h-100"> <!-- h-100 para que todas las tarjetas tengan la misma altura -->
                            @if ($novel->cover_image)
                                <img src="{{ $novel->cover_image }}" class="card-img-top img-fluid"
                                    alt="{{ $novel->title }}" style="max-width: 100%;" width="625" height="1000">
                            @else
                                <img src="https://via.placeholder.com/150" class="card-img-top img-fluid"
                                    alt="Portada no disponible" style="height: 150px; object-fit: cover;">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $novel->title }}</h5> <!-- Título centrado -->
                                <p class="text-muted">{{ $novel->user->username }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center">
            {{ $novels->links() }}
        </div>
    </div>
@endsection
