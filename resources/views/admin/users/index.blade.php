@extends('layouts.admin')

@section('title', 'Usuarios | Novelas _try')

@section('typeAdmin', 'Usuarios')

@section('content')

    <div class="container">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3" role="button">
            Crear Usuario
        </a>

        <table id="mitable" class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre de Usuario</th>
                    <th scope="col">Tipo de Usuario</th>
                    <th scope="col">Imagen de Perfil</th>
                    <th scope="col">Novelas</th>
                    <th scope="col">Accion</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->username }}</td>
                        <td>
                            @if ($user->user_type == 1)
                                Usuario Normal
                            @elseif($user->user_type == 2)
                                Administrador
                            @else
                                Desconocido
                            @endif
                        </td>
                        <td>{{ $user->profile_image }}</td>
                        <td>{{ $user->novels_count }}</td> <!-- Muestra el número de novelas asociadas -->
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('profiles.show', $user->username) }}" class="btn btn-light" role="button"
                                    target="_blank">
                                    Ver
                                </a>

                                <a href="{{ route('admin.users.edit', $user->username) }}" class="btn btn-secondary"
                                    role="button">
                                    Editar
                                </a>

                                <a href="{{ route('admin.users.delete', $user->username) }}" class="btn btn-danger" role="button">
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
