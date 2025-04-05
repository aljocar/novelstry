<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Novelas _try')</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> --}}

    {{-- Bootstrap Icon --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    {{-- <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <style>
        body {
            background-color: rgb(241, 241, 245);
        }
    </style>

</head>

<body>

    <div class="container py-4 col-lg-5 my-5">
        <div class="row justify-content-center">

            <div class="card border-0 container-fluid shadow-lg text-muted">
                <div class="card-body">
                    <div class="mt-5">

                        <div class="mt-3 mb-3">
                            <h3 class="text-center">@yield('typeSesion', 'Iniciar Sesión')</h3>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Contenido Principal --}}
                        <div class="container">

                            @yield('content')

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>



    {{-- Java --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script> --}}

    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

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
    </script>

</body>

</html>
