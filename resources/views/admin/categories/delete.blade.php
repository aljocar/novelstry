@extends('layouts.admin')

@section('title', 'Eliminar Categoria - ' . $category->name . ' | Novelas _try')

@section('typeAdmin', 'Eliminar Categoria - ' . $category->name)

@section('content')

    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary mb-3">Volver</a>

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
        ¿Estas seguro que quieres <strong>Eliminar</strong> esta categoria?
    </div>

    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" id="deleteForm">
        @csrf
        @method('DELETE')

        <div class="row">
            <div class="form-label mb-3 col-12 col-md-6">
                <label for="name"><strong>Nombre de la categoría</strong></label>
                <input type="text" id="name" name="name" class="form-control"
                    value="{{ old('name', $category->name) }}" disabled>
            </div>
        </div>

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
