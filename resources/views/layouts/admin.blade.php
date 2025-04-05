<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Novelas _try')</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Bootstrap CSS -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> --}}
    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    {{-- Bootstrap Icon --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">


    {{-- Datatables --}}
    <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}">

    {{-- Graficos ApexCharts --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}
    <script src="{{ asset('js/apexcharts.min.js') }}"></script>

    <!-- Estilos personalizados -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>

<body>

    <!-- Navbar -->
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
            <div class="container-fluid">
                <button id="sidebarToggle" class="btn btn-light">☰</button>
                <a class="navbar-brand" href="{{ route('home') }}">
                    <h4>Novelas _try</h4>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
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
                                        <img src="{{ asset('storage/' . $user->profile_image) }}" class="img-fluid shadow"
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

    <!-- Contenedor principal -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky">

                    <ul class="nav flex-column">

                        <li class="nav-item border-bottom pb-2 mb-2">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <strong>
                                    <h5>Dashboard</h5>
                                </strong>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.novel.index') }}">
                                Novelas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.chapter.index') }}">
                                Capitulos
                            </a>
                        </li>
                        <li class="nav-item border-bottom pb-2 mb-2">
                            <a class="nav-link" href="{{ route('admin.comments.index') }}">
                                Comentarios
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.categories.index') }}">
                                Categorias
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.statistics.index') }}">
                                Estadisticas
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.users.index') }}">
                                Usuarios
                            </a>
                        </li>

                    </ul>

                </div>
            </nav>

            <!-- Contenido principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">

                <!-- Contenido dinámico -->
                <div class="container bg-white rounded-0 p-3 mx-2">

                    <div
                        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">@yield('typeAdmin', 'Dashboard')</h1>
                    </div>

                    @yield('content')
                </div>

            </main>

        </div>
    </div>

    {{-- <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script> --}}

    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <!-- Imagenes subir y recortar -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css"> --}}
    <link rel="stylesheet" href="{{ asset('vendor/cropperjs/cropper.min.css') }}">
    <script src="{{ asset('vendor/cropperjs/cropper.min.js') }}"></script>

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

    <!-- Script Datadable -->
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>

    <script src="{{ asset('vendor/datatables/datatables.js') }}"></script>
    <script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#mitable').DataTable({
                language: {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ ",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "No hay datos disponibles en esta tabla",
                    "sInfo": "Mostrando de _START_ a _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "sInfoFiltered": "(filtrado de _MAX_ registros en total)",
                    "sInfoPostFix": "",
                    "sSearch": "Buscar:",
                    "sUrl": "",
                    "oAria": {
                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                },
                responsive: true,
                dom: 'Bfrtilp',
                buttons: [{
                        extend: 'excelHtml5',
                        text: '<i class="bi bi-file-earmark-spreadsheet-fill"></i> ',
                        titleAttr: 'Exportar a Excel',
                        className: 'btn btn-success',
                        exportOptions: {
                            columns: ':not(:last-child)' // Excluye la última columna
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="bi bi-file-earmark-pdf-fill"></i> ',
                        titleAttr: 'Exportar a PDF',
                        className: 'btn btn-danger',
                        exportOptions: {
                            columns: ':not(:last-child)' // Excluye la última columna
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="bi bi-printer-fill"></i> ',
                        titleAttr: 'Imprimir',
                        className: 'btn btn-secondary',
                        exportOptions: {
                            columns: ':not(:last-child)' // Excluye la última columna
                        }
                    },
                ],
                order: [
                    [0, 'desc']
                ],
                columnDefs: [{
                    targets: 0,
                    orderable: true
                }]
            });
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

    <!-- Script para controlar el sidebar -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                sidebarToggle.classList.toggle('active');

                if (sidebarToggle.classList.contains('active')) {
                    sidebarToggle.innerHTML = '✕';
                } else {
                    sidebarToggle.innerHTML = '☰';
                }
            });

            document.addEventListener('click', function(event) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickInsideSidebarToggle = sidebarToggle.contains(event.target);

                if (!isClickInsideSidebar && !isClickInsideSidebarToggle) {
                    sidebar.classList.remove('active');
                    sidebarToggle.classList.remove('active');
                    sidebarToggle.innerHTML = '☰';
                }
            });
        });
    </script>
</body>

</html>
