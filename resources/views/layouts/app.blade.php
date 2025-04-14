<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Novelas _try')</title>
    
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Bootstrap CSS -->
    {{-- <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    {{-- Bootstrap Icon --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Estilos personalizados -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>

    <header>

        <nav class="navbar navbar-expand-lg bg-body-tertiary mb-3">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <h4>Novelas _try</h4>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">

                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page"
                                href="{{ route('novels.index') }}">Novelas</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ route('profiles.index') }}">Usuarios</a>
                        </li>

                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('favorites.index') }}">Mis Favoritos</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('novels.create') }}">Crear</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Mis Favoritos</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Crear</a>
                            </li>
                        @endauth

                    </ul>

                    <!-- Menú de Usuario (Iniciar Sesión o Cerrar Sesión) -->
                    <ul class="navbar-nav ms-auto">
                        @auth

                            <li class="nav-item dropdown">

                                @php
                                    $user = Auth::user();
                                @endphp

                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <strong>
                                        <img src="{{ $user->profile_image }}" class="img-fluid shadow"
                                            width="30px" alt="Imagen de Perfil">
                                        {{ Auth::user()->username }}
                                    </strong> <!-- Mostrar el nombre de usuario -->
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end">

                                    <li><a class="dropdown-item"
                                            href="{{ route('profiles.show', Auth::user()->username) }}">Perfil</a></li>

                                    <li><a class="dropdown-item"
                                            href="{{ route('profiles.config', Auth::user()->username) }}">Configuración</a>
                                    </li>

                                    @if (Auth::check() && Auth::user()->user_type == 2)
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">Panel de
                                            Administración</a>
                                    @endif

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>

                                    <li class="nav-item">
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">Cerrar Sesión</button>
                                        </form>
                                    </li>

                                </ul>
                            </li>
                        @else
                            <!-- Si el usuario no está autenticado, mostrar "Iniciar Sesión" -->
                            <li class="nav-item">
                                <a class="nav-link btn btn-light" href="{{ route('login') }}">Iniciar Sesión</a>
                            </li>
                        @endauth
                    </ul>

                </div>
            </div>
        </nav>

    </header>

    {{-- Mostrar mensajes de error --}}
    @if (session('errorAdmin'))
        <div class="alert alert-danger">
            {{ session('errorAdmin') }}
        </div>
    @endif

    {{-- Contenido Principal --}}
    <div class="container">

        <div class="card border-0 container-fluid shadow-lg">
            <div class="card-body">


                @yield('content')

            </div>
        </div>

    </div>



    {{-- Java --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script> --}}

    <!-- Al final del body -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <!-- jQuery (requerido para Bootstrap) -->

    <!-- Imagenes subir y recortar -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    {{-- <link rel="stylesheet" href="{{ asset('vendor/cropperjs/cropper.min.css') }}">
    <script src="{{ asset('vendor/cropperjs/cropper.min.js') }}"></script> --}}

    {{-- Script Recortar Imagen --}}
    <script>
        var cropper;

        document.getElementById('cover_image').addEventListener('change', function(event) {
            var file = event.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function() {
                    var imagePreview = document.getElementById('image-preview');
                    imagePreview.src = reader.result;
                    imagePreview.style.display = 'block';

                    // Destruir la instancia anterior de Cropper si existe
                    if (cropper) {
                        cropper.destroy();
                    }

                    // Inicializar Cropper.js
                    cropper = new Cropper(imagePreview, {
                        aspectRatio: 5 / 8, // Proporción 5:8 (portada de novela)
                        viewMode: 1,
                        autoCropArea: 1,
                        responsive: true,
                        crop: function(event) {
                            // Obtener el canvas recortado
                            var canvas = cropper.getCroppedCanvas({
                                width: 625, // Ancho deseado
                                height: 1000 // Alto deseado
                            });
                            // Convertir el canvas a una imagen en base64
                            var croppedImage = canvas.toDataURL('image/jpeg');
                            // Asignar la imagen recortada al campo oculto
                            document.getElementById('cropped_image').value = croppedImage;
                        }
                    });
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

    {{-- Boton Ver Contraseña --}}
    <script>
        function togglePasswordVisibility(inputId, buttonId) {
            document.getElementById(buttonId).addEventListener('click', function() {
                const passwordInput = document.getElementById(inputId);
                const icon = this.querySelector('i');

                // Alternar entre tipo "password" y "text"
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash'); // Cambiar a ícono de ojo abierto
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye'); // Cambiar a ícono de ojo cerrado
                }
            });
        }

        // Aplicar la función a ambos campos
        togglePasswordVisibility('password', 'togglePassword');
        togglePasswordVisibility('password_confirmation', 'toggleConfirmPassword');
        togglePasswordVisibility('current_password', 'toggleCurrentPassword');
    </script>

    {{-- Script para mostrar la modal automáticamente --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('Modal'));
                myModal.show();
            });
        </script>
    @endif

</body>

</html>
