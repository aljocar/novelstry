@extends('layouts.app')

@section('title', $users->username . ' - Editar Usuario | Novelas _try')

@section('content')

    <div class="d-flex justify-content-between align-items-center">
        <!-- Nombre de usuario -->
        <h3 class="card-title">Editar Cuenta</h3>

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

    <form method="POST" action="{{ route('profiles.update', $users->username) }}">
        @csrf
        @method('PUT')

        <div class="form-label mb-3 col-12 col-md-6">
            <label for="username">Nombre de usuario</label>
            <input type="text" id="username" name="username" class="form-control"
                value="{{ old('username', $users->username) }}" required>
        </div>

        <div class="row">
            <!-- Botón que activa el despliegue -->
            <div class="col-12 mb-3">
                <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#passwordFields"
                    aria-expanded="false" aria-controls="passwordFields">
                    Cambiar contraseña
                </button>
            </div>
        </div>

        <!-- Contenido que se despliega -->
        <div class="row collapse" id="passwordFields">
            <!-- Campo para la contraseña actual -->
            <div class="form-label col-12 col-md-4 mb-4">
                <label for="current_password">Contraseña actual</label>
                <div class="input-group">
                    <input type="password" id="current_password" name="current_password" class="form-control">
                    <button class="btn btn-outline-secondary" type="button" id="toggleCurrentPassword">
                        <i class="bi bi-eye"></i> <!-- Ícono de ojo cerrado -->
                    </button>
                </div>
            </div>

            <!-- Campo para la nueva contraseña -->
            <div class="form-label col-12 col-md-4 mb-4">
                <label for="password">Nueva contraseña</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bi bi-eye"></i> <!-- Ícono de ojo cerrado -->
                    </button>
                </div>
            </div>

            <!-- Campo para confirmar la nueva contraseña -->
            <div class="form-label col-12 col-md-4 mb-4">
                <label for="password_confirmation">Confirmar nueva contraseña</label>
                <div class="input-group">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                        <i class="bi bi-eye"></i> <!-- Ícono de ojo cerrado -->
                    </button>
                </div>
            </div>
        </div>

        <button type="submit" class="form-control btn btn-primary">Actualizar Perfil</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordFields = document.getElementById('passwordFields');
            const currentPasswordField = document.getElementById('current_password');
            const passwordField = document.getElementById('password');
            const passwordConfirmField = document.getElementById('password_confirmation');

            // Inicialmente deshabilitados
            [currentPasswordField, passwordField, passwordConfirmField].forEach(field => {
                field.disabled = true;
            });

            // Cuando se muestra la sección de contraseñas
            document.querySelector('[data-bs-target="#passwordFields"]').addEventListener('click', function() {
                const isCollapsed = !passwordFields.classList.contains('show');

                [currentPasswordField, passwordField, passwordConfirmField].forEach(field => {
                    field.disabled = !isCollapsed;
                    if (!isCollapsed) {
                        field.value = ''; // Limpiar campos al colapsar
                    }
                });
            });
        });
    </script>

@endsection
