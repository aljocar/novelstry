<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Novel;
use App\Models\User;
use App\Models\UserMetadata;
use App\Services\ImgurService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Obtener las 10 novelas más recientemente actualizadas con su último capítulo
        $novels = Novel::with(['latestChapter', 'user'])
            ->has('chapters')
            ->orderByDesc(
                Chapter::select('created_at')
                    ->whereColumn('novel_id', 'novels.id')
                    ->latest()
                    ->limit(1)
            )
            ->take(10)
            ->get();

        // Obtener conteos
        $novelCount = Novel::count();
        $chapterCount = Chapter::count();
        $commentCount = Comment::count();
        $userCount = User::count();

        return view('admin.dashboard', compact('novels', 'novelCount', 'chapterCount', 'commentCount', 'userCount'));
    }

    public function index()
    {
        $users = User::orderBy('id', 'desc')
            ->withCount('novels') // Esto añade un atributo `novels_count` a cada categoría
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request, ImgurService $imgurService)
    {
        if (auth()->user()->user_type != 2) {
            return redirect()->route('novels.index')->with('errorAdmin', 'No tienes el permiso necesario.');
        }

        // Validación personalizada
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'min:5', 'max:20', 'unique:users'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
            'user_type' => ['required', 'in:1,2'],
            'real_name' => ['nullable', 'min:3', 'max:255'],
            'address' => ['nullable', 'min:3'],
            'bibliography' => ['nullable', 'min:5'],
            'contact' => ['nullable', 'min:5'],
            'cropped_image' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    // Verificar si es una imagen base64 válida
                    if (!preg_match('/^data:image\/(\w+);base64,/', $value, $matches)) {
                        $fail('El archivo no es una imagen válida.');
                        return;
                    }

                    // Obtener el tipo de imagen (jpeg, png, etc.)
                    $imageType = $matches[1];

                    // Verificar que el tipo de imagen sea soportado
                    if (!in_array($imageType, ['jpeg', 'png', 'jpg', 'svg'])) {
                        $fail('El tipo de imagen no es soportado. Use JPEG, PNG, JPG o SVG.');
                        return;
                    }

                    // Decodificar la imagen base64 y calcular su tamaño
                    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $value));
                    $imageSize = strlen($imageData);

                    // Verificar que la imagen no pese más de 2 MB
                    if ($imageSize > 2097152) { // 2 MB en bytes
                        $fail('La imagen no debe pesar más de 2 MB.');
                    }
                },
            ],
        ], [
            // Mensajes de Error
            'username.required' => 'El usuario es obligatorio.',
            'username.min' => 'El usuario debe tener al menos 5 caracteres.',
            'username.max' => 'El usuario debe tener máximo 20 caracteres.',
            'username.unique' => 'El usuario ya existe.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 4 caracteres.',
            'password.confirmed' => 'La contraseña de confirmación no coincide.',
            'user_type.required' => 'El tipo de usuario es obligatorio.',
            'user_type.in' => 'El tipo de usuario seleccionado no es válido.',
            'real_name.min' => 'El Nombre debe tener al menos 3 caracteres.',
            'real_name.max' => 'El Nombre debe tener máximo 255 caracteres.',
            'address.min' => 'La Dirección debe tener al menos 3 caracteres.',
            'bibliography.min' => 'La Descripción debe tener al menos 5 caracteres.',
            'contact.min' => 'La Información de Contacto debe tener al menos 5 caracteres.',
        ]);

        // Si la validación falla, redirigir con errores
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Manejar la imagen de perfil con Imgur
        $profileImageUrl = 'https://i.imgur.com/8h8Lu67.png'; // URL por defecto

        if ($request->cropped_image) {
            $imgurUrl = $imgurService->uploadBase64Image($request->cropped_image);

            if ($imgurUrl) {
                $profileImageUrl = $imgurUrl;
            } else {
                return redirect()->back()
                    ->with('error', 'No se pudo subir la imagen de perfil. Inténtalo nuevamente.')
                    ->withInput();
            }
        }

        // Crear el usuario
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'profile_image' => $profileImageUrl, // Usamos la URL de Imgur
        ]);

        // Crear la metadata asociada al usuario
        UserMetadata::create([
            'real_name' => $request->real_name,
            'address' => $request->address,
            'bibliography' => $request->bibliography,
            'contact' => $request->contact,
            'user_id' => $user->id, // Asignar el ID del usuario recién creado
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function edit($username)
    {
        // Buscar el usuario por su username
        $user = User::where('username', $username)->first();

        // Verificar si el usuario existe
        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'Usuario no encontrado.');
        }

        // Obtener los metadatos del usuario si existen
        $user->load('user_metadata');

        // Pasar el usuario a la vista
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user, ImgurService $imgurService)
    {
        // Verificar si el usuario autenticado es un administrador
        if (auth()->user()->user_type != 2) {
            return redirect()->route('novels.index')->with('errorAdmin', 'No tienes el permiso necesario.');
        }

        // Validación personalizada
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'min:5', 'max:20', 'unique:users,username,' . $user->id],
            'password' => ['nullable', 'string', 'min:4', 'confirmed'], // La contraseña es opcional
            'user_type' => ['required', 'in:1,2'],
            'real_name' => ['nullable', 'min:3', 'max:255'],
            'address' => ['nullable', 'min:3'],
            'bibliography' => ['nullable', 'min:5'],
            'contact' => ['nullable', 'min:5'],
            'cropped_image' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^data:image\/(\w+);base64,/', $value, $matches)) {
                        $fail('El archivo no es una imagen válida.');
                        return;
                    }

                    $imageType = $matches[1];

                    if (!in_array($imageType, ['jpeg', 'png', 'jpg', 'svg'])) {
                        $fail('El tipo de imagen no es soportado. Use JPEG, PNG, JPG o SVG.');
                        return;
                    }

                    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $value));
                    $imageSize = strlen($imageData);

                    if ($imageSize > 2097152) {
                        $fail('La imagen no debe pesar más de 2 MB.');
                    }
                },
            ],
        ], [
            'username.required' => 'El usuario es obligatorio.',
            'username.min' => 'El usuario debe tener al menos 5 caracteres.',
            'username.max' => 'El usuario debe tener máximo 20 caracteres.',
            'username.unique' => 'El usuario ya existe.',
            'password.min' => 'La contraseña debe tener al menos 4 caracteres.',
            'password.confirmed' => 'La contraseña de confirmación no coincide.',
            'user_type.required' => 'El tipo de usuario es obligatorio.',
            'user_type.in' => 'El tipo de usuario seleccionado no es válido.',
            'real_name.min' => 'El Nombre debe tener al menos 3 caracteres.',
            'real_name.max' => 'El Nombre debe tener máximo 255 caracteres.',
            'address.min' => 'La Dirección debe tener al menos 3 caracteres.',
            'bibliography.min' => 'La Descripción debe tener al menos 5 caracteres.',
            'contact.min' => 'La Información de Contacto debe tener al menos 5 caracteres.',
        ]);

        // Si la validación falla, redirigir con errores
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Manejar la imagen de perfil con Imgur
        $profileImageUrl = $user->profile_image; // Mantener la imagen actual por defecto

        if ($request->cropped_image) {
            $imgurUrl = $imgurService->uploadBase64Image($request->cropped_image);

            if ($imgurUrl) {
                $profileImageUrl = $imgurUrl;
            } else {
                return redirect()->back()
                    ->with('error', 'No se pudo actualizar la imagen de perfil. Inténtalo nuevamente.')
                    ->withInput();
            }
        }

        // Preparar datos del usuario
        $userData = [
            'username' => $request->username,
            'user_type' => $request->user_type,
            'profile_image' => $profileImageUrl,
        ];

        // Actualizar la contraseña solo si se proporciona una nueva
        if ($request->password) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Verificar si existe user_metadata
        if ($user->user_metadata) {
            // Si existe, actualizar los metadatos
            $user->user_metadata->update([
                'real_name' => $request->real_name,
                'address' => $request->address,
                'bibliography' => $request->bibliography,
                'contact' => $request->contact,
            ]);
        } else {
            // Si no existe, crear un nuevo registro de metadatos
            UserMetadata::create([
                'real_name' => $request->real_name,
                'address' => $request->address,
                'bibliography' => $request->bibliography,
                'contact' => $request->contact,
                'user_id' => $user->id, // Asignar el ID del usuario
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function delete($username)
    {
        // Buscar el usuario por su username
        $user = User::where('username', $username)->first();

        // Verificar si el usuario existe
        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'Usuario no encontrado.');
        }

        $novels = $user->novels()->orderBy('id', 'desc')->get();

        return view('admin.users.delete', compact('user', 'novels'));
    }

    public function destroy(User $user)
    {
        // Verificar si el usuario autenticado es un administrador
        if (auth()->user()->user_type != 2) {
            return redirect()->route('novels.index')->with('errorAdmin', 'No tienes el permiso necesario.');
        }

        // Validar que el campo de confirmación tenga el valor correcto
        if (request('confirmDelete') !== 'Eliminar') {
            return redirect()->back()->withErrors(['confirmDelete' => 'Debes escribir "Eliminar" para confirmar.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminada exitosamente.');
    }
}
