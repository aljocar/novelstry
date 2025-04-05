@extends('layouts.app')

@section('title', 'Eliminar Capitulo - ' . $chapter->title . ' - ' . $novel->title . ' - Novelas _try')

@section('content')

    @if ($fromTable)
        <a href="{{ route('admin.chapter.index') }}" class="btn btn-secondary mb-3">Volver a la tabla</a>
    @else
        <a href="{{ route('chapters.show', $chapter) }}" class="btn btn-secondary mb-3">Volver</a>
    @endif

    <h4 class="card-title mb-4">Eliminar Capitulo de {{ $novel->title }}</h4>

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
        ¿Estas seguro que quieres <strong>Eliminar</strong> este capitulo?
    </div>

    <form action="{{ route('chapters.destroy', [$novel, $chapter]) }}" method="POST">

        @csrf
        @method('DELETE')

        <!-- Contenedor principal con flexbox de Bootstrap -->
        <div class="d-flex flex-row gap-3">
            <!-- Campo 1: Título -->
            <div class="form-label flex-grow-1">
                <label for="title"><strong>Titulo</strong></label>
                <input type="text" id="title" name="title" class="form-control"
                    value="{{ old('title', $chapter->title) }}" disabled>
            </div>

            <!-- Campo 2: Autor -->
            <div class="form-label flex-grow-1">
                <label for="author"><strong>Novela</strong></label>
                <input type="text" id="author" name="author" class="form-control"
                    value="{{ old('title', $novel->title) }}" disabled>
            </div>
        </div>

        <button type="submit" class="form-control btn btn-danger mt-3">Eliminar</button>
    </form>

    </div>
    </div>

@endsection
