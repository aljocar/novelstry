@extends('layouts.app')

@section('title', $novel->title . ' - Capitulos | Novelas _try')

@section('content')

    <a href="{{ route('novels.show', $novel) }}" class="btn text-muted">
        <h1 class="text-2xl mb-3">{{ $novel->title }}</h1>
    </a>

    <div class="row">
        <div class="col-12">

            <!-- Botón para cambiar el orden -->
            <div class="mb-3 d-flex">
                {{ $chapters->onEachSide(2)->links() }} <!-- Limita la cantidad de páginas visibles -->

                <div class="ms-auto">
                    @if (request()->query('order') === 'desc')
                        <a href="{{ request()->fullUrlWithQuery(['order' => 'asc']) }}" class="btn btn-primary">
                            &darr;
                        </a>
                    @else
                        <a href="{{ request()->fullUrlWithQuery(['order' => 'desc']) }}" class="btn btn-primary">
                            &uarr;
                        </a>
                    @endif
                </div>
            </div>

            <div class="card rounded-0">
                <div class="card-body">
                    <div class="row"> <!-- Contenedor de filas -->
                        @php
                            // Calcula el número inicial basado en la página actual, el límite por página y el orden
                            $perPage = $chapters->perPage(); // Elementos por página
                            $currentPage = $chapters->currentPage(); // Página actual
                            $total = $chapters->total(); // Total de capítulos

                            // Si el orden es ascendente, el contador comienza desde (currentPage - 1) * perPage + 1
                            // Si el orden es descendente, el contador comienza desde total - (currentPage - 1) * perPage
                            if (request()->query('order') === 'desc') {
                                $startNumber = $total - ($currentPage - 1) * $perPage;
                            } else {
                                $startNumber = ($currentPage - 1) * $perPage + 1;
                            }
                        @endphp

                        @foreach ($chapters as $chapter)
                            <!-- En pantallas grandes (md y superior), ocupa 6 columnas (2 por fila) -->
                            <!-- En pantallas pequeñas (sm y inferior), ocupa 12 columnas (1 por fila) -->
                            <div class="col-12 col-md-6 mb-0">
                                <style>
                                    .custom-btn {
                                        color: #333;
                                        /* Color del texto */
                                        text-align: left;
                                        /* Alinear texto a la izquierda */
                                    }
                                </style>

                                <a href="{{ route('chapters.show', [$novel, $chapter]) }}"
                                    class="btn custom-btn btn-light rounded-0 w-100 py-3">
                                    @if (request()->query('order') === 'desc')
                                        {{ $startNumber - $loop->index }} - <strong>{{ $chapter->title }}</strong>
                                    @else
                                        {{ $startNumber + $loop->index }} - <strong>{{ $chapter->title }}</strong>
                                    @endif
                                </a>

                                {{-- @auth
                                    @if ($novel->user_id == auth()->id() || auth()->user()->user_type == 2)
                                        <div class="">
                                            <a href="{{ route('chapters.edit', [$novel, $chapter]) }}" class="btn btn-secondary">
                                                Editar
                                            </a>

                                            <a href="{{ route('chapters.delete', [$novel, $chapter]) }}" class="btn btn-danger">
                                                Eliminar
                                            </a>
                                        </div>
                                    @endif
                                @endauth --}}

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
{{-- 
    <div class="row">
        <div class="col-12">

            <!-- Botón para cambiar el orden -->
            <div class="mb-3 d-flex">
                {{ $chapters->links() }} <!-- Aquí llamas a links() en la colección paginada -->

                <div class="ms-auto">
                    @if (request()->query('order') === 'asc')
                        <a href="{{ request()->fullUrlWithQuery(['order' => 'desc']) }}" class="btn btn-primary">
                            &darr;
                        </a>
                    @else
                        <a href="{{ request()->fullUrlWithQuery(['order' => 'asc']) }}" class="btn btn-primary">
                            &uarr;
                        </a>
                    @endif
                </div>
            </div>

            <div class="card rounded-0">
                <div class="card-body">
                    <div class="row"> <!-- Contenedor de filas -->
                        @foreach ($chapters as $chapter)
                            <!-- En pantallas grandes (md y superior), ocupa 6 columnas (2 por fila) -->
                            <!-- En pantallas pequeñas (sm y inferior), ocupa 12 columnas (1 por fila) -->
                            <div class="col-12 col-md-6 mb-0">
                                <style>
                                    .custom-btn {
                                        color: #333; /* Color del texto */
                                        text-align: left; /* Alinear texto a la izquierda */
                                    }
                                </style>
            
                                <a href="{{ route('chapters.show', [$novel, $chapter]) }}"
                                    class="btn custom-btn btn-light rounded-0 w-100 py-3">
                                    <strong>{{ $chapter->title }}</strong>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div> 
--}}
