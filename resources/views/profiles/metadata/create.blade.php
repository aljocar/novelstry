@extends('layouts.app')

@section('title', 'Añadir Bibliografia - ' . $users->username . ' | Novelas _try')

@section('content')

    <div class="d-flex justify-content-between align-items-center">
        <!-- Nombre de usuario -->
        <h3 class="card-title">Añadir Perfil</h3>

        <a href="{{ route('profiles.config', Auth::user()->username) }}" class="mb-3 btn btn-secondary">Volver</a>
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

    <form method="POST" action="{{ route('profiles.metadata.store', $users->username) }}">
        @csrf

        <div class="row">

            <div class="form-label mb-3 col-12 col-md-4">
                <label for="real_name">Nombre Verdadero</label>
                <input type="text" id="real_name" name="real_name" class="form-control" value="{{ old('real_name') }}">
            </div>

            <div class="form-label mb-3 col-12 col-md-4">
                <label for="address">Dirección</label>
                <input type="text" id="address" name="address" class="form-control" value="{{ old('address') }}">
            </div>

            <div class="form-label mb-3 col-12 col-md-4">
                <label for="contact">Informacion de Contacto</label>
                <input type="text" id="contact" name="contact" class="form-control" value="{{ old('contact') }}">
            </div>
            
        </div>

        <div class="form-label mb-4 col-12 col-md-6">
            <label for="bibliography">Descripción</label>
            <textarea name="bibliography" id="bibliography" class="form-control" rows="7">{{ old('bibliography') }}</textarea>
        </div>

        <button type="submit" id="submitButton" class="form-control btn btn-primary" disabled>Actualizar Perfil</button>
    </form>

    <script>
        // Selecciona todos los campos de entrada y el textarea
        const inputs = document.querySelectorAll('#real_name, #address, #contact, #bibliography');
        const submitButton = document.getElementById('submitButton');
    
        // Función para verificar si algún campo tiene contenido
        function checkInputs() {
            let isFilled = false;
            inputs.forEach(input => {
                if (input.value.trim() !== '') {
                    isFilled = true;
                }
            });
    
            // Habilita o deshabilita el botón según si hay contenido
            submitButton.disabled = !isFilled;
        }
    
        // Escucha el evento 'input' en cada campo
        inputs.forEach(input => {
            input.addEventListener('input', checkInputs);
        });
    </script>

@endsection
