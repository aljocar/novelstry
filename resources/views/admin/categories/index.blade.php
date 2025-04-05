@extends('layouts.admin')

@section('title', 'Categorias | Novelas _try')

@section('typeAdmin', 'Categorias')

@section('content')

    <div class="container">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mb-3" role="button">
            Crear Categoria
        </a>

        <table id="mitable" class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Novelas Asignadas</th>
                    <th scope="col">Accion</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->novels_count }}</td> <!-- Muestra el número de novelas asociadas -->
                        <td>
                            <div class="d-flex gap-2">

                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-secondary"
                                    role="button">
                                    Editar
                                </a>

                                <a href="{{ route('admin.categories.delete', $category) }}" class="btn btn-danger"
                                    role="button">
                                    Eliminar
                                </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

@endsection
