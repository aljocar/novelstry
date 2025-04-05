@extends('layouts.app')

@section('title', 'Novelas _try')

{{-- @section('title', 'Página de Inicio') --}}

<style>
    .text-shadow {
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        /* Sombra de texto fuerte */
    }

    .carousel-caption {
        background: rgba(255, 255, 255, 0.2);
        /* Fondo semitransparente */
        padding: 20px;
        border-radius: 10px;
    }

    .carousel-caption h5 {
        font-size: 24px;
        font-weight: bold;
    }

    .carousel-caption p {
        font-size: 16px;
    }
</style>

@section('content')

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <h1 class="mb-4">Inicio</h1>

    <div class="container">

        <div class="row">

            <div class="col-12 col-md-5">
                {{-- Ultimas Actualizaciones --}}
                <div class="card rounded-0">
                    <div class="card-body">
                        <div class="row">
                            <h4 class="mb-4 text-muted">Ultimas Actualizaciones</h4>
                            @foreach ($novels as $novel)
                                <div class="col-6 col-md-6 mb-4">
                                    <a href="{{ route('novels.show', $novel) }}" class="text-decoration-none text-dark">
                                        <div class="card h-100">
                                            @if ($novel->cover_image)
                                                <img src="{{ asset('storage/' . $novel->cover_image) }}"
                                                    class="card-img-top img-fluid" alt="{{ $novel->title }}"
                                                    style="max-width: 100%;" width="625" height="1000">
                                            @else
                                                <img src="https://via.placeholder.com/150" class="card-img-top img-fluid"
                                                    alt="Portada no disponible" style="height: 150px; object-fit: cover;">
                                            @endif
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $novel->title }}</h5>
                                                <!-- Mostrar información del capítulo más reciente -->
                                                @if ($novel->latestChapter)
                                                    <p class="text-muted">{{ $novel->latestChapter->title }}</p>
                                                    <p class="text-muted">
                                                        {{ $novel->latestChapter->created_at->diffForHumans() }}</p>
                                                @else
                                                    <p class="text-muted">No hay capítulos disponibles</p>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-7">
                {{-- Mas Vistas --}}
                <div class="card rounded-0 mb-4">
                    <div class="card-body">
                        <div class="row">
                            <h4 class="mb-4 text-muted">Las Más Vistas</h4>
                            <div id="novelsCarousel" class="carousel slide" data-bs-ride="carousel">
                                <!-- Indicadores del carrusel -->
                                <div class="carousel-indicators">
                                    @for ($i = 0; $i < $novels->count(); $i++)
                                        <button type="button" data-bs-target="#novelsCarousel"
                                            data-bs-slide-to="{{ $i }}" class="{{ $i === 0 ? 'active' : '' }}"
                                            aria-current="true" aria-label="Slide {{ $i + 1 }}"></button>
                                    @endfor
                                </div>

                                <!-- Slides del carrusel -->
                                <div class="carousel-inner">
                                    @foreach ($novels as $novel)
                                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                            <img src="{{ $novel->cover_image ? asset('storage/' . $novel->cover_image) : 'https://via.placeholder.com/800x400' }}"
                                                class="d-block w-100" alt="{{ $novel->title }}"
                                                style="height: 400px; object-fit: cover;">
                                            <div class="carousel-caption d-none d-md-block text-white text-shadow">
                                                <h5 class="shadow-lg">{{ $novel->title }}</h5>
                                                @if ($novel->latestChapter)
                                                    <p class="shadow-lg">{{ $novel->latestChapter->title }}</p>
                                                    <p class="shadow-lg">
                                                        {{ $novel->latestChapter->created_at->diffForHumans() }}</p>
                                                @else
                                                    <p class="shadow-lg">No hay capítulos disponibles</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Controles del carrusel -->
                                <button class="carousel-control-prev" type="button" data-bs-target="#novelsCarousel"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#novelsCarousel"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Siguiente</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
