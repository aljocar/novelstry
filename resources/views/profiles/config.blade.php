@extends('layouts.app')

@section('title', $users->username . ' - Editar Usuario | Novelas _try')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center">
        <!-- Nombre de usuario -->
        <h3 class="card-title">Configuración</h3>
        <a href="{{ route('profiles.show', $users->username) }}" class="mb-3 btn btn-secondary">Volver a Perfil</a>
    </div>

    <!-- Contenedor de las tarjetas -->
    <div class="d-flex flex-column gap-3">
        <!-- Tarjeta para el botón "Editar Perfil" -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Perfil</h5>
                <p class="card-text">
                    Aquí puedes editar la información de tu perfil.
                </p>

                @if ($userMetadata)
                    <!-- Si el usuario tiene metadata, mostrar botón de editar -->
                    <a href="{{ route('profiles.metadata.edit', $users->username) }}" class="btn btn-secondary">Editar
                        Perfil</a>
                @else
                    <!-- Si el usuario no tiene metadata, mostrar botón de añadir -->
                    <a href="{{ route('profiles.metadata.create', $users->username) }}" class="btn btn-secondary">Añadir
                        Bibliografia</a>
                @endif
            </div>
        </div>

        <!-- Tarjeta para el botón "Editar Imagen" -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Imagen de Perfil</h5>
                <img src="{{ asset('storage/' . $users->profile_image) }}" class="img-fluid shadow mt-3 mb-3" width="100px" alt="Imagen de Perfil">
                <p class="card-text">Aquí puedes editar la imagen de tu perfil.</p>
                <a href="{{ route('profiles.image.edit', Auth::user()->username) }}" class="btn btn-secondary">Editar Imagen</a>
            </div>
        </div>

        <!-- Tarjeta para el botón "Editar Cuenta" -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Cuenta</h5>
                <p class="card-text">Aquí puedes editar la configuración de tu cuenta.</p>
                <a href="{{ route('profiles.edit', Auth::user()->username) }}" class="btn btn-secondary">Editar Cuenta</a>
            </div>
        </div>

        <!-- Tarjeta para el botón "Eliminar Cuenta" -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Eliminar Cuenta</h5>
                <p class="card-text">Cuidado: Esta acción sera irreversible.</p>
                <a href="{{ route('profiles.delete', Auth::user()->username) }}" class="btn btn-danger">Eliminar Cuenta</a>
            </div>
        </div>
    </div>
@endsection
