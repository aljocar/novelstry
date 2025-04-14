@extends('layouts.admin')

@section('title', 'Editar Usuario - ' . $user->username . ' | Novelas _try')

@section('typeAdmin', 'Editar Usuario - ' . $user->username)

@section('content')

    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mb-3">Volver</a>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') <!-- Especificar el método PUT para la actualización -->

        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="username" class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control" name="username" id="username"
                    value="{{ old('username', $user->username) }}" required>
            </div>

            <div class="form-label mb-4 col-12 col-md-6">
                <label for="profile_image">Imagen de Perfil</label>
                <input type="file" id="profile_image" name="profile_image" class="form-control">
                <input type="hidden" id="cropped_image" name="cropped_image">

                <!-- Mostrar la imagen actual -->
                @if ($user->profile_image)
                    <img id="image-preview" src="{{ $user->profile_image }}" alt="Preview"
                        style="max-width: 50%; margin-top: 10px;">
                @else
                    <img id="image-preview" src="#" alt="Preview"
                        style="display: none; max-width: 50%; margin-top: 10px;">
                @endif
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="password" class="form-label">Nueva Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" name="password" id="password">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <small class="text-muted">Deja este campo vacío si no deseas cambiar la contraseña.</small>
            </div>

            <div class="mb-3 col-12 col-md-6">
                <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="mb-4 col-12 col-md-6">
            <label for="user_type" class="form-label">Tipo de Usuario</label>
            <select class="form-select" name="user_type" id="user_type" required>
                <option value="1" {{ old('user_type', $user->user_type) == 1 ? 'selected' : '' }}>Usuario Normal
                </option>
                <option value="2" {{ old('user_type', $user->user_type) == 2 ? 'selected' : '' }}>Administrador
                </option>
            </select>
        </div>

        <hr class="my-4">

        <h5>Datos Adicionales</h5>
        <p class="text-muted mb-4">No son obligatorios.</p>

        <div class="row">
            <div class="mb-3 col-12 col-md-6">
                <label for="real_name" class="form-label">Nombre Real</label>
                <input type="text" class="form-control" name="real_name" id="real_name"
                    value="{{ old('real_name', $user->metadata->real_name ?? '') }}">
            </div>

            <div class="mb-3 col-12 col-md-6">
                <label for="address" class="form-label">Dirección</label>
                <input type="text" class="form-control" name="address" id="address"
                    value="{{ old('address', $user->metadata->address ?? '') }}">
            </div>

            <div class="mb-3 col-12 col-md-6">
                <label for="bibliography" class="form-label">Descripción</label>
                <textarea class="form-control" name="bibliography" id="bibliography">{{ old('bibliography', $user->metadata->bibliography ?? '') }}</textarea>
            </div>

            <div class="mb-3 col-12 col-md-6">
                <label for="contact" class="form-label">Información de Contacto</label>
                <input type="text" class="form-control" name="contact" id="contact"
                    value="{{ old('contact', $user->metadata->contact ?? '') }}">
            </div>
        </div>

        <button type="submit" class="form-control btn btn-primary btn-block mt-3 mb-3">Editar Usuario</button>
    </form>

    {{-- Script Recortar Imagen --}}
    <script>
        var cropper;

        document.getElementById('profile_image').addEventListener('change', function(event) {
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
                        aspectRatio: 1, // Proporción 1:1 (cuadrada)
                        viewMode: 1,
                        autoCropArea: 1,
                        responsive: true,
                        crop: function(event) {
                            // Obtener el canvas recortado
                            var canvas = cropper.getCroppedCanvas({
                                width: 300, // Ancho deseado
                                height: 300 // Alto deseado
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

@endsection
