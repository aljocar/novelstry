@extends('layouts.app')

@section('title', 'Mis Favoritos | Novelas _try')

@section('content')

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <h1 class="mb-4">Mis Favoritos</h1>

    <hr class="my-4"> <!-- Línea horizontal con margen arriba y abajo -->

    <div class="container">
        @if ($favorites->isEmpty())
            <p class="text-muted">No tienes ninguna novela en favoritos.</p>
        @else
            <div class="row">
                @foreach ($favorites as $novel)
                    <div class="col-6 col-md-2 mb-4">
                        <a href="{{ route('novels.show', $novel) }}" class="text-decoration-none text-dark">
                            <div class="card h-100">
                                @if ($novel->cover_image)
                                    <img src="{{ $novel->cover_image }}" class="card-img-top img-fluid"
                                        alt="{{ $novel->title }}" style="max-width: 100%;" width="625" height="1000">
                                @else
                                    <img src="https://via.placeholder.com/150" class="card-img-top img-fluid"
                                        alt="Portada no disponible" style="height: 150px; object-fit: cover;">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $novel->title }}</h5>
                                </div>

                                @if (auth()->user()->favorites->contains($novel))
                                    <a href="{{ route('novels.unfavorite', $novel) }}" class="btn btn-danger btn-sm rounded-0">
                                        Eliminar de favoritos
                                    </a>
                                @endif

                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
