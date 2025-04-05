<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')
            ->withCount('novels') // Esto añade un atributo `novels_count` a cada categoría
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->user()->user_type != 2) {
            return redirect()->route('novels.index')->with('errorAdmin', 'No tienes el permiso necesario.');
        }

        $request->validate([
            'name' => 'required|string|min:3|max:20|unique:categories,name',
        ], [
            // Mensajes de Error
            'name.required' => 'El Nombre es obligatorio.',
            'name.min' => 'El Nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El Nombre debe tener máximo 20 caracteres.',
            'name.unique' => 'El Nombre ya está en uso.',
        ]);

        Category::create($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        if (auth()->user()->user_type != 2) {
            return redirect()->route('novels.index')->with('errorAdmin', 'No tienes el permiso necesario.');
        }

        $request->validate([
            'name' => 'required|string|min:3|max:20|unique:categories,name',
        ], [
            // Mensajes de Error
            'name.required' => 'El Nombre es obligatorio.',
            'name.min' => 'El Nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El Nombre debe tener máximo 20 caracteres.',
            'name.unique' => 'El Nombre ya está en uso.',
        ]);

        $category->update($request->all());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría actualizada exitosamente.');
    }

    public function delete(Category $category, Request $request)
    {
        return view('admin.categories.delete', compact('category'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if (auth()->user()->user_type != 2) {
            return redirect()->route('novels.index')->with('errorAdmin', 'No tienes el permiso necesario.');
        }

        // Validar que el campo de confirmación tenga el valor correcto
        if (request('confirmDelete') !== 'Eliminar') {
            return redirect()->back()->withErrors(['confirmDelete' => 'Debes escribir "Eliminar" para confirmar.']);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría eliminada exitosamente.');
    }
}
