@extends('layouts.admin')

@section('title', 'Capitulos | Novelas _Try')

@section('typeAdmin', 'Capitulos')

@section('content')

    <div class="container">
    
        <table id="mitable" class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Titulo</th>
                    <th scope="col">Autor</th>
                    <th scope="col">Novela</th>
                    <th scope="col">Accion</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($chapters as $chapter)
                    <tr>
                        <td>{{ $chapter->id }}</td>
                        <td>{{ $chapter->title }}</td>
                        <td>{{ $chapter->novel->user->username }}</td>
                        <td>{{ $chapter->novel->title }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('chapters.show', ['novel' => $chapter->novel, 'chapter' => $chapter]) }}" class="btn btn-light" role="button" target="_blank">
                                    Ver
                                </a>

                                <a href="{{ route('chapters.edit', ['novel' => $chapter->novel, 'chapter' => $chapter, 'from_table' => true]) }}" class="btn btn-secondary" role="button">
                                    Editar
                                </a>
        
                                <a href="{{ route('chapters.delete', ['novel' => $chapter->novel, 'chapter' => $chapter, 'from_table' => true]) }}" class="btn btn-danger" role="button">
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