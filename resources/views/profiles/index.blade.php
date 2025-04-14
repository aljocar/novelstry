@extends('layouts.app')

@section('title', 'Usuarios | Novelas _try')

{{-- @section('title', 'Página de Inicio') --}}

@section('content')

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <h1 class="text-2xl">Usuarios</h1>

    <div class="container">

        <!-- Formulario de búsqueda -->
        <form action="{{ route('profiles.index') }}" method="GET" class="mb-4 col-12 col-md-6">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                    placeholder="Buscar usuario" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>

        @if ($users->isEmpty())
            <div class="card-text text-muted mb-4">
                No se encontraron resultados para "{{ $search }}".
            </div>
        @endif

        <div class="row">
            @foreach ($users as $user)
                <div class="col-6 col-md-2 mb-4"> <!-- Ajusta las columnas según el diseño -->
                    <a href="{{ route('profiles.show', $user->username) }}" class="text-decoration-none text-dark">
                        <div class="card h-100 shadow-sm"> <!-- Agrega sombra y altura fija -->
                            <div class="card-body text-center p-3"> <!-- Centra el contenido y agrega relleno -->
                                @if ($user->profile_image)
                                    <img src="{{ $user->profile_image }}" 
                                         class="img-fluid rounded-circle mb-3" 
                                         alt="{{ $user->username }}" 
                                         style="width: 100px; height: 100px; object-fit: cover;"> <!-- Imagen redonda y tamaño fijo -->
                                @else
                                    <img src="https://via.placeholder.com/150" 
                                         class="img-fluid rounded-circle mb-3" 
                                         alt="Imagen de perfil no disponible" 
                                         style="width: 100px; height: 100px; object-fit: cover;"> <!-- Imagen redonda y tamaño fijo -->
                                @endif
                                <p class="text-muted mb-0">{{ $user->username }}</p> <!-- Nombre de usuario -->
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    </div>
@endsection
