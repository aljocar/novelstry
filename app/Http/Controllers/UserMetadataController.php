<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserMetadata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMetadataController extends Controller
{
    public function create($users)
    {
        $users = Auth::user();

        return view('profiles.metadata.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $username)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Verificar que el usuario solo pueda editar su propio perfil
        if ($user->username !== $username) {
            abort(403, 'No tienes permiso para editar esta cuenta.');
        }

        // Validación
        $request->validate([
            'real_name' => ['nullable', 'min:3', 'max:255'],
            'address' => 'nullable|min:3',
            'bibliography' => 'nullable|min:5',
            'contact' => 'nullable|min:5',
        ], [
            // Mensajes de error
            'real_name.min' => 'El Nombre debe tener al menos 3 caracteres.',
            'real_name.max' => 'El Nombre debe tener máximo 255 caracteres.',
            'address.min' => 'La Dirección debe tener al menos 3 caracteres.',
            'bibliography.min' => 'La Descripción debe tener al menos 5 caracteres.',
            'contact.min' => 'La Informacion de Contacto debe tener al menos 5 caracteres.',
        ]);

        // Crear la metadata asociada al usuario
        $userMetadata = UserMetadata::create([
            'real_name' => $request->real_name,
            'address' => $request->address,
            'bibliography' => $request->bibliography,
            'contact' => $request->contact,
            'user_id' => $user->id, // Asignar el ID del usuario autenticado
        ]);

        // Redirigir al perfil del usuario
        return redirect()->route('profiles.show', $user->username)
            ->with('success', 'Perfil actualizado correctamente.');
    }

    public function edit($users)
    {
        $users = Auth::user();

        return view('profiles.metadata.edit', compact('users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $username)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Verificar que el usuario solo pueda editar su propio perfil
        if ($user->username !== $username) {
            abort(403, 'No tienes permiso para editar esta cuenta.');
        }

        // Validación
        $request->validate([
            'real_name' => ['nullable', 'min:3', 'max:255'],
            'address' => 'nullable|min:3',
            'bibliography' => 'nullable|min:5',
            'contact' => 'nullable|min:5',
        ], [
            // Mensajes de error
            'real_name.min' => 'El Nombre debe tener al menos 3 caracteres.',
            'real_name.max' => 'El Nombre debe tener máximo 255 caracteres.',
            'address.min' => 'La dirección debe tener al menos 3 caracteres.',
            'bibliography.min' => 'La descripción debe tener al menos 5 caracteres.',
            'contact.min' => 'La Informacion de Contacto debe tener al menos 5 caracteres.',
        ]);

        // Obtener el registro de metadata del usuario (solo si existe)
        $userMetadata = UserMetadata::where('user_id', $user->id)->firstOrFail();

        // Actualizar los campos
        $userMetadata->update([
            'real_name' => $request->real_name,
            'address' => $request->address,
            'bibliography' => $request->bibliography,
            'contact' => $request->contact,
        ]);

        // Redirigir al perfil del usuario
        return redirect()->route('profiles.show', $user->username)
            ->with('success', 'Perfil actualizado correctamente.');
    }
}
