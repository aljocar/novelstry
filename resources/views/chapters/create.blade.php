@extends('layouts.app')

@section('title', 'Nuevo Capitulo - ' . $novel->title . ' | Novelas _try')

@section('content')

    <h4 class="card-title mb-4">Nuevo Capitulo de {{$novel->title}}</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('chapters.store', $novel) }}" method="POST">

        @csrf

        <div class="form-label mb-3">
            <label for="title">Titulo del Capitulo</label>
            <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required>
        </div>

        <div class="form-label mb-4">
            <label for="content">Contenido</label>
            <textarea name="content" id="content" class="form-control" rows="15" cols="200" required>{{ old('content') }}</textarea>
        </div>

        <button type="submit" class="form-control btn btn-primary">Crear</button>
    </form>

@endsection
