@extends('layouts.app')

@section('title', 'Editar Capitulo - ' . $chapter->title . ' - ' . $novel->title . ' - Novelas _try')

@section('content')

    @if ($fromTable)
        <a href="{{ route('admin.chapter.index') }}" class="btn btn-secondary mb-3">Volver a la tabla</a>
    @else
        <a href="{{ route('chapters.show', $chapter) }}" class="btn btn-secondary mb-3">Volver</a>
    @endif

    <h4 class="card-title mb-4">Editar Capitulo de {{ $novel->title }}</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('chapters.update', [$novel, $chapter]) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="form-label mb-3">
            <label for="title">Titulo del Capitulo</label>
            <input type="text" id="title" name="title" class="form-control"
                value="{{ old('title', $chapter->title) }}" required>
        </div>

        <div class="form-label mb-4">
            <label for="content">Contenido</label>
            <textarea name="content" id="content" class="form-control" rows="15" cols="200" required>{{ old('content', $chapter->content) }}</textarea>
        </div>

        <button type="submit" class="form-control btn btn-primary">Editar</button>
    </form>

@endsection
