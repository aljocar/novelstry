@extends('layouts.app')

@section('title', 'Eliminar Novela ' . $novel->title . ' | Novelas _try')

@section('content')

    @if ($fromTable)
        <a href="{{ route('admin.novel.index') }}" class="btn btn-secondary mb-3">Volver a la tabla</a>
    @else
        <a href="{{ route('novels.show', $novel) }}" class="btn btn-secondary mb-3">Volver</a>
    @endif


    <h5 class="card-title">Eliminar Novela</h5>

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
        ¿Estas seguro que quieres <strong>Eliminar</strong> esta novela?
    </div>

    <form action="{{ route('novels.destroy', $novel) }}" method="POST" id="deleteForm">
        @csrf
        @method('DELETE')

        <!-- Contenedor principal con flexbox de Bootstrap -->
        <div class="d-flex flex-row gap-3">
            <!-- Campo 1: Título -->
            <div class="form-label flex-grow-1">
                <label for="title"><strong>Titulo</strong></label>
                <input type="text" id="title" name="title" class="form-control"
                    value="{{ old('title', $novel->title) }}" disabled>
            </div>

            <!-- Campo 2: Autor -->
            <div class="form-label flex-grow-1">
                <label for="author"><strong>Autor</strong></label>
                <input type="text" id="author" name="author" class="form-control"
                    value="{{ old('author', $novel->user->username) }}" disabled>
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
