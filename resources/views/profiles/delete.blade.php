@extends('layouts.app')

@section('title', 'Eliminar Cuenta - ' . $users->username . ' | Novelas _try')

@section('content')

    <div class="d-flex justify-content-between align-items-center">
        <!-- Nombre de usuario -->
        <h3 class="card-title">Eliminar Cuenta</h3>

        <a href="{{ route('profiles.config', $users->username) }}" class="mb-3 btn btn-secondary">Volver</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="alert alert-danger" role="alert">
        ¿Estas seguro que quieres <strong>Eliminar</strong> su cuenta?
    </div>

    <form action="{{ route('profiles.destroy', $users->username) }}" method="POST" id="deleteForm">
        @csrf
        @method('DELETE')

        <!-- Contenedor principal con flexbox de Bootstrap -->
        <div class="d-flex flex-row gap-3">
            <!-- Campo 1: Título -->
            <div class="form-label flex-grow-1">
                <label for="username"><strong>Nombre de usuario</strong></label>
                <input type="text" id="username" name="username" class="form-control"
                    value="{{ old('username', $users->username) }}" disabled>
            </div>

        </div>

        <!-- Campo de confirmación -->
        <div class="form-label mt-3">
            <label for="confirmDelete"><strong>Escribe "Eliminar" para confirmar</strong></label>
            <input type="text" id="confirmDelete" name="confirmDelete" class="form-control" required>
        </div>

        <!-- Botón de eliminación (inicialmente deshabilitado) -->
        <button type="submit" class="form-control btn btn-danger mt-3" id="deleteButton" disabled>Eliminar</button>
    </form>

    <!-- Separación vertical -->
    <hr class="my-4"> <!-- Línea horizontal con margen arriba y abajo -->

    <h3 class="mt-4 mb-4">Novelas</h3>
    @if ($novels->isEmpty())
        <p>Este usuario no ha creado ninguna novela aún.</p>
    @else
        <div class="row">
            @foreach ($novels as $novel)
                <div class="col-3 col-md-2 mb-4">
                    <a href="{{ route('novels.show', $novel) }}" class="text-decoration-none text-dark">
                        <!-- Enlace que cubre toda la tarjeta -->
                        <div class="card h-100"> <!-- h-100 para que todas las tarjetas tengan la misma altura -->
                            @if ($novel->cover_image)
                                <img src="{{ asset('storage/' . $novel->cover_image) }}" class="card-img-top img-fluid"
                                    alt="{{ $novel->title }}" style="max-width: 100%;" width="625" height="1000">
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

    <!-- Script para habilitar el botón de eliminación -->
    <script>
        document.getElementById('confirmDelete').addEventListener('input', function() {
            var deleteButton = document.getElementById('deleteButton');
            if (this.value === 'Eliminar') {
                deleteButton.disabled = false;
            } else {
                deleteButton.disabled = true;
            }
        });
    </script>

@endsection
