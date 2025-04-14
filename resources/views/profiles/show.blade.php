@extends('layouts.app')

@section('title', $user->username . ' | Novelas _try')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">

            <div class="d-flex justify-content-between align-items-center">
                <!-- Nombre de usuario -->
                <h2 class="mb-0">
                    {{ $user->username }}
                </h2>

                <!-- Botón "Editar Perfil" (solo visible si el usuario está autenticado y es el dueño del perfil) -->
                @auth
                    @if (Auth::user()->username === $user->username)
                        <div>
                            <a href="{{ route('profiles.config', $user->username) }}"
                                class="btn btn-secondary">Configuración</a>
                        </div>
                    @endif
                @endauth
            </div>

            <img src="{{ $user->profile_image }}" class="img-fluid shadow mt-3 mb-3" width="200px"
                alt="Imagen de Perfil">

            <div class="card border-0">
                <div class="card-body text-muted">
                    <h5 class="card-title">Datos del Perfil</h5>
                    @if ($user->user_metadata)
                        @php
                            $hasData =
                                $user->user_metadata->real_name ||
                                $user->user_metadata->address ||
                                $user->user_metadata->contact ||
                                $user->user_metadata->bibliography;
                        @endphp

                        @if ($hasData)
                            @if ($user->user_metadata->real_name)
                                <p class="card-text">Nombre Verdadero: <b>{{ $user->user_metadata->real_name }}</b></p>
                            @endif

                            @if ($user->user_metadata->address)
                                <p class="card-text">Direccion: <b>{{ $user->user_metadata->address }}</b></p>
                            @endif

                            @if ($user->user_metadata->contact)
                                <p class="card-text">Contacto: <b>{{ $user->user_metadata->contact }}</b></p>
                            @endif

                            @if ($user->user_metadata->bibliography)
                                <p class="card-text">Descripcion: <b>{{ $user->user_metadata->bibliography }}</b></p>
                            @endif
                        @else
                            <p class="card-text">No hay datos adicionales disponibles.</p>
                        @endif
                    @else
                        <p class="card-text">No hay datos adicionales disponibles.</p>
                    @endif
                </div>
            </div>

            <!-- Separación vertical -->
            <hr class="my-4"> <!-- Línea horizontal con margen arriba y abajo -->

            <h3 class="mt-4 mb-4">Novelas</h3>
            @if ($novels->isEmpty())
                <p class="text-muted">Este usuario no ha creado ninguna novela aún.</p>
            @else
                <div class="row">
                    @foreach ($novels as $novel)
                        <div class="col-6 col-md-2 mb-4">
                            <a href="{{ route('novels.show', $novel) }}" class="text-decoration-none text-dark">
                                <!-- Enlace que cubre toda la tarjeta -->
                                <div class="card h-100"> <!-- h-100 para que todas las tarjetas tengan la misma altura -->
                                    @if ($novel->cover_image)
                                        <img src="{{ $novel->cover_image }}"
                                            class="card-img-top img-fluid" alt="{{ $novel->title }}"
                                            style="max-width: 100%;" width="625" height="1000">
                                    @else
                                        <img src="https://via.placeholder.com/150" class="card-img-top img-fluid"
                                            alt="Portada no disponible" style="height: 150px; object-fit: cover;">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $novel->title }}</h5> <!-- Título centrado -->
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

@endsection
