<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Mostrar formulario de registro
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Procesar el registro
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:5|max:20|unique:users',
            'password' => 'required|string|min:4|confirmed',
        ], [
            //Mensaje de Error
            'username.required' => 'El usuario es obligatorio.',
            'username.min' => 'El usuario debe tener al menos 5 caracteres.',
            'username.max' => 'El usuario debe tener maximo 20 caracteres.',
            'username.unique' => 'El usuario ya existe.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 4 caracteres.',
            'password.confirmed' => 'La contraseña de confirmacion no coincide.',
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type ?? 1, // Por defecto, usuario normal
            'profile_image' => 'defaults/default_avatar.jpg',
        ]);

        Auth::login($user);

        return redirect()->route('novels.index');
    }

    // Mostrar formulario de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesar el login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ], [
            //Mensaje de Error
            'username.required' => 'El usuario es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('novels.index'));
        }

        return back()->withErrors([
            'username' => 'El usuario no coincide.',
            'password' => 'La contraseña no coincide.',
        ]);
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('novels.index');
    }

}
