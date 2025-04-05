<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
    ];

    //public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        // Generar el slug cuando se crea una nueva categoría
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });

        // Actualizar el slug cuando se edita el nombre de la categoría
        static::updating(function ($category) {
            if ($category->isDirty('name')) { // Verifica si el campo 'name' ha cambiado
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function novels()
    {
        return $this->belongsToMany(Novel::class);
    }
}
