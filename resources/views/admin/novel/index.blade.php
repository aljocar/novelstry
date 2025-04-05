@extends('layouts.admin')

@section('title', 'Novelas | Novelas _Try')

@section('typeAdmin', 'Novelas')

@section('content')

    <div class="container">
    
        <table id="mitable" class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Titulo</th>
                    <th scope="col">Autor</th>
                    <th scope="col">Capitulos</th>
                    <th scope="col">Visitas</th>
                    <th scope="col">Favoritos</th>
                    <th scope="col">Accion</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($novels as $novel)
                    <tr>
                        <td>{{ $novel->id }}</td>
                        <td>{{ $novel->title }}</td>
                        <td>{{ $novel->user->username }}</td>
                        <td>{{ $novel->chapters_count }}</td>
                        <td>{{ $novel->visits->count() }}</td>
                        <td>{{ $novel->favoritedBy->count() }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('novels.show', $novel) }}" class="btn btn-light" role="button" target="_blank">
                                    Ver
                                </a>

                                <a href="{{ route('novels.edit', ['novel' => $novel, 'from_table' => true]) }}" class="btn btn-secondary" role="button">
                                    Editar
                                </a>
    
                                <a href="{{ route('novels.delete', ['novel' => $novel, 'from_table' => true]) }}" class="btn btn-danger" role="button">
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