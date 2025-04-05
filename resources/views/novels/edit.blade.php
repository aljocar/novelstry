@extends('layouts.app')

@section('title', 'Editar Novela - ' . $novel->title . ' | Novelas _try')

@section('content')

    @if ($fromTable)
        <a href="{{ route('admin.novel.index') }}" class="btn btn-secondary mb-3">Volver a la tabla</a>
    @else
        <a href="{{ route('novels.show', $novel) }}" class="btn btn-secondary mb-3">Volver</a>
    @endif


    <h5 class="card-title">Editar Novela - {{ $novel->title }}</h5>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('novels.update', $novel) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="form-label mb-3 col-12 col-md-6">
                <label for="title">Título</label>
                <input type="text" id="title" name="title" class="form-control"
                    value="{{ old('title', $novel->title) }}" required>
            </div>

            <div class="form-label mb-4 col-12 col-md-6">
                <label for="cover_image">Portada</label>
                <input type="file" id="cover_image" name="cover_image" class="form-control">
                <!-- Campo oculto para la imagen recortada -->
                <input type="hidden" id="cropped_image" name="cropped_image">
                <!-- Mostrar la imagen actual -->
                @if ($novel->cover_image)
                    <img id="image-preview" src="{{ asset('storage/' . $novel->cover_image) }}" alt="Preview"
                        style="max-width: 50%; margin-top: 10px;">
                @else
                    <img id="image-preview" src="#" alt="Preview"
                        style="display:none; max-width: 50%; margin-top: 10px;">
                @endif
            </div>
        </div>

        <div class="row">
            <div class="form-label col-12 col-md-6 mb-4">
                <label for="synopsis">Sinopsis</label>
                <textarea name="synopsis" id="synopsis" class="form-control" rows="10" cols="200" required>{{ old('synopsis', $novel->synopsis) }}</textarea>
            </div>

            <!-- Campo para seleccionar categorías con checkboxes -->
            <div class="form-label col-12 col-md-6 mb-4">
                <label for="categories">Categorías</label>
                <div class="row">
                    @foreach ($categories as $category)
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]"
                                    id="category_{{ $category->id }}" value="{{ $category->id }}"
                                    {{ in_array($category->id, $novel->categories->pluck('id')->toArray()) ? 'checked' : '' }}>
                                <label class="form-check-label" for="category_{{ $category->id }}">
                                    {{ $category->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <button type="submit" class="form-control btn btn-primary">Editar</button>
    </form>

@endsection
