<?php

namespace App\Http\Controllers;

use App\Models\Novel;
use App\Models\User;
use App\Models\UserMetadata;
use App\Services\ImgurService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el término de búsqueda
        $search = $request->input('search');

        // Consulta base para los usuarios
        $query = User::orderBy('username', 'asc');

        // Si hay un término de búsqueda, aplicar filtro
        if ($search) {
            $query->where('username', 'like', "%{$search}%");
        }

        // Paginar los resultados
        $users = $query->paginate(12);

        // Pasar los resultados a la vista
        return view('profiles.index', compact('users', 'search'));
    }

    public function show($username)
    {
        // Obtener el usuario por su nombre de usuario
        $user = User::where('username', $username)->firstOrFail();

        // Obtener las novelas creadas por el usuario
        $novels = $user->novels()->orderBy('id', 'desc')->get();

        return view('profiles.show', compact('user', 'novels'));
    }

    public function config($username)
    {
        // Obtener el usuario por su nombre de usuario
        $users = User::where('username', $username)->firstOrFail();

        // Obtener las novelas creadas por el usuario
        $novels = $users->novels;

        $userMetadata = UserMetadata::where('user_id', $users->id)->first();

        return view('profiles.config', compact('users', 'userMetadata'));
    }

    public function edit($username)
    {
        // Obtener el usuario autenticado
        $users = Auth::user();

        // Verificar que el usuario solo pueda editar su propio perfil
        if ($users->username !== $username) {
            abort(403, 'No tienes permiso para editar esta cuenta.');
        }

        return view('profiles.edit', compact('users'));
    }

    public function update(Request $request, $username)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Verificar que el usuario solo pueda editar su propio perfil
        if ($user->username !== $username) {
            abort(403, 'No tienes permiso para editar esta cuenta.');
        }

        // Reglas de validación condicionales
        $rules = [
            'username' => [
                'required',
                'string',
                'min:5',
                'max:20',
                Rule::unique('users', 'username')->ignore($user->id)
            ],
            'password' => 'nullable|string|min:4|confirmed',
        ];

        // Solo validar current_password si se está cambiando la contraseña
        $messages = [
            'username.required' => 'El nombre de usuario es obligatorio.',
            'username.min' => 'El nombre de usuario debe tener al menos 5 caracteres.',
            'username.max' => 'El nombre de usuario no puede exceder los 20 caracteres.',
            'username.unique' => 'Este nombre de usuario ya está en uso.',
            'password.min' => 'La nueva contraseña debe tener al menos 4 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ];

        if ($request->filled('password')) {
            $rules['current_password'] = 'required|string|min:4';
            $messages['current_password.required'] = 'La contraseña actual es obligatoria para cambiar la contraseña.';
            $messages['current_password.min'] = 'La contraseña actual debe tener al menos 4 caracteres.';
        }

        // Validar los datos
        $validatedData = $request->validate($rules, $messages);

        // Verificar contraseña actual solo si se está cambiando la contraseña
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'La contraseña actual es incorrecta.'])
                    ->withInput();
            }
        }

        // Actualizar los datos
        $user->username = $validatedData['username'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        // Redirigir con mensaje de éxito
        return redirect()
            ->route('profiles.show', $user->username)
            ->with('success', 'Perfil actualizado correctamente.');
    }

    public function delete($username)
    {
        // Obtener el usuario autenticado
        $users = Auth::user();

        $novels = $users->novels()->orderBy('id', 'desc')->get();

        // Verificar que el usuario solo pueda eliminar su propia cuenta
        if ($users->username !== $username) {
            abort(403, 'No tienes permiso para eliminar esta cuenta.');
        }

        return view('profiles.delete', compact('users', 'novels'));
    }

    public function destroy($username)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Verificar que el usuario solo pueda eliminar su propia cuenta
        if ($user->username !== $username) {
            abort(403, 'No tienes permiso para eliminar esta cuenta.');
        }

        // Validar que el campo de confirmación tenga el valor correcto
        if (request('confirmDelete') !== 'Eliminar') {
            return redirect()->back()->withErrors(['confirmDelete' => 'Debes escribir "Eliminar" para confirmar.']);
        }

        // Cerrar la sesión del usuario antes de eliminar la cuenta
        Auth::logout();

        $user->delete();

        return redirect()->route('novels.index');
    }

    public function imageEdit($username)
    {
        // Obtener el usuario autenticado
        $users = Auth::user();

        // Verificar que el usuario solo pueda editar su propio perfil
        if ($users->username !== $username) {
            abort(403, 'No tienes permiso para editar esta imagen.');
        }

        return view('profiles.image.edit', compact('users'));
    }

    public function imageUpdate(Request $request, $username, ImgurService $imgurService)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Verificar que el usuario solo pueda editar su propio perfil
        if ($user->username !== $username) {
            abort(403, 'No tienes permiso para editar esta imagen.');
        }

        // Validación personalizada para la imagen
        $validator = Validator::make($request->all(), [
            'cropped_image' => [
                'required',
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
            'cropped_image.required' => 'La imagen es obligatoria.',
        ]);

        // Si la validación falla, redirigir con errores
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Manejar la imagen de avatar con Imgur
        if ($request->cropped_image) {
            $imgurUrl = $imgurService->uploadBase64Image($request->cropped_image);

            if ($imgurUrl) {
                // Actualizar con la nueva URL de Imgur
                $user->profile_image = $imgurUrl;
                $user->save();
            } else {
                return redirect()->back()
                    ->with('error', 'No se pudo actualizar la imagen de perfil. Inténtalo nuevamente.')
                    ->withInput();
            }
        }

        return redirect()->route('profiles.show', $user->username)
            ->with('success', 'Foto de perfil actualizada correctamente.');
    }
}
