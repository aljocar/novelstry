@extends('layouts.admin')

@section('title', 'Crear Categoria | Novelas _try')

@section('typeAdmin', 'Nueva Categoria')

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

    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        
        <div class="row">
            <div class="form-label mb-3 col-12 col-md-6">
                <label for="name">Nombre de la categoría</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
        </div>
    
        <button type="submit" class="form-control btn btn-primary">Crear</button>
    </form>

@endsection
