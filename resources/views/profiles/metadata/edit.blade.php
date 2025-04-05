@extends('layouts.app')

@section('title', 'Editar Bibliografia - ' . $users->username . ' | Novelas _try')

@section('content')

    <div class="d-flex justify-content-between align-items-center">
        <!-- Nombre de usuario -->
        <h3 class="card-title">Editar Perfil</h3>
    
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

    <form method="POST" action="{{ route('profiles.metadata.update', $users->username) }}">
        @csrf
        @method('PUT')

        <div class="row">

            <div class="form-label mb-3 col-12 col-md-4">
                <label for="real_name">Nombre Verdadero</label>
                <input type="text" id="real_name" name="real_name" class="form-control" value="{{ old('real_name', $users->user_metadata->real_name) }}">
            </div>

            <div class="form-label mb-3 col-12 col-md-4">
                <label for="address">Dirección</label>
                <input type="text" id="address" name="address" class="form-control" value="{{ old('address', $users->user_metadata->address) }}">
            </div>

            <div class="form-label mb-3 col-12 col-md-4">
                <label for="contact">Informacion de Contacto</label>
                <input type="text" id="contact" name="contact" class="form-control" value="{{ old('contact', $users->user_metadata->contact) }}">
            </div>

        </div>

        <div class="form-label mb-4 col-12 col-md-6">
            <label for="bibliography">Descripción</label>
            <textarea name="bibliography" id="bibliography" class="form-control" rows="7">{{ old('bibliography', $users->user_metadata->bibliography) }}</textarea>
        </div>

        <button type="submit" class="form-control btn btn-primary">Actualizar Perfil</button>
    </form>


@endsection
