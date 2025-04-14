@extends('layouts.app')

@section('title', 'Editar Imagen - ' . $users->username . ' | Novelas _try')

@section('content')

    <h5 class="card-title">Editar Imagen - {{ $users->username }}</h5>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profiles.image.update', $users->username) }}" method="POST" enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="row">

            <div class="form-label mb-4 col-12 col-md-6">
                <label for="profile_image">Imagen de Perfil</label>
                <input type="file" id="profile_image" name="profile_image" class="form-control">

                <!-- Campo oculto para la imagen recortada -->
                <input type="hidden" id="cropped_image" name="cropped_image">

                <!-- Mostrar la imagen actual -->
                @if ($users->profile_image)
                    <img id="image-preview" src="{{ $users->profile_image }}" alt="Preview"
                        style="max-width: 50%; margin-top: 10px;">
                @else
                    <img id="image-preview" src="#" alt="Preview"
                        style="display:none; max-width: 50%; margin-top: 10px;">
                @endif
            </div>

        </div>

        <button type="submit" class="form-control btn btn-primary">Editar</button>
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
