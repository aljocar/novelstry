@extends('layouts.admin')

@section('title', 'Panel de Administrador | Novelas _try')

@section('typeAdmin', 'Panel de Administrador')

@section('content')

    <div class="container">

        <div class="row mb-4">
            <!-- Card 1: Conteo de Novelas -->
            <div class="col-6 col-md-3 mb-2">
                <a href="{{ route('admin.novel.index') }}" class="card text-decoration-none rounded-0">
                    <div class="card-body text-center text-muted">
                        <h5 class="card-title">Novelas</h5>
                        <p class="card-text display-4">{{ $novelCount }}</p>
                    </div>
                </a>
            </div>

            <!-- Card 2: Conteo de Capítulos -->
            <div class="col-6 col-md-3 mb-2">
                <a href="{{ route('admin.chapter.index') }}" class="card text-decoration-none rounded-0">
                    <div class="card-body text-center text-muted">
                        <h5 class="card-title">Capítulos</h5>
                        <p class="card-text display-4">{{ $chapterCount }}</p>
                    </div>
                </a>
            </div>

            <!-- Card 3: Conteo de Comentarios -->
            <div class="col-6 col-md-3 mb-2">
                <a href="{{ route('admin.comments.index') }}" class="card text-decoration-none rounded-0">
                    <div class="card-body text-center text-muted">
                        <h5 class="card-title">Comentarios</h5>
                        <p class="card-text display-4">{{ $commentCount }}</p>
                    </div>
                </a>
            </div>

            <!-- Card 4: Conteo de Usuarios -->
            <div class="col-6 col-md-3 mb-2">
                <a href="{{ route('admin.users.index') }}" class="card text-decoration-none rounded-0">
                    <div class="card-body text-center text-muted">
                        <h5 class="card-title">Usuarios</h5>
                        <p class="card-text display-4">{{ $userCount }}</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="card rounded-0">
            <div class="card-body">
                <h4 class="mb-4">Ultimas Actualizaciones</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Titulo</th>
                            <th scope="col">Autor</th>
                            <th scope="col">Nombre del Capitulo</th>
                            <th scope="col">Última Actualización</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($novels as $novel)
                            @foreach ($novel->chapters as $chapter)
                                <tr>
                                    <td>{{ $novel->title }}</td>
                                    <td>{{ $novel->user->username }}</td>
                                    <td>{{ $chapter->title }}</td>
                                    <td>{{ $chapter->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection
