@extends('layouts.app-login')

@section('title', 'Iniciar Sesión')

@section('typeSesion', 'Iniciar Sesión')

@section('content')

    <form method="POST" action="{{ route('login') }}">

        @csrf

        <div class="mb-3">
            <label for="username" class="form-label">Usuario</label>
            <input type="text" class="form-control" name="username" id="username" value="{{ old('username') }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>

            <div class="input-group">
                <input type="password" class="form-control" name="password" id="password" required>

                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="bi bi-eye"></i> <!-- Ícono de ojo cerrado -->
                </button>
            </div>

        </div>

        <button type="submit" class="btn btn-primary btn-block mt-3 mb-3">Iniciar sesión</button>

        <a href="{{ route('register') }}" class="btn btn-light mt-3 mb-3">Registrarse</a>

    </form>

@endsection
