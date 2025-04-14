<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Novel extends Model
{
    //
    use HasFactory;

    protected $table = 'novels';

    protected $fillable = [
        'title',
        'slug',
        'synopsis',
        'user_id',
        'cover_image',
    ];

    //public $timestamps = false;

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // app/Models/Novel.php
    public function getCoverImageUrlAttribute()
    {
        // Si es una URL de Imgur (contiene "imgur.com")
        if (str_contains($this->cover_image, 'imgur.com')) {
            return $this->cover_image;
        }

        // Si es una ruta local pero no empieza con http
        if ($this->cover_image && !str_starts_with($this->cover_image, 'http')) {
            return asset('storage/' . $this->cover_image);
        }

        // Imagen por defecto
        return asset('defaults/default_cover.jpg');
    }

    protected static function boot()
    {
        parent::boot();

        // Generar automáticamente el slug antes de crear el registro
        static::creating(function ($novel) {
            $slug = Str::slug($novel->title);
            $code = mt_rand(1000, 9999); // Código aleatorio de 4 dígitos
            $novel->slug = "{$slug}-{$code}";

            // Verificar si el slug ya existe y generar uno nuevo si es necesario
            while (Novel::where('slug', $novel->slug)->exists()) {
                $code = mt_rand(1000, 9999); // Generar un nuevo código
                $novel->slug = "{$slug}-{$code}";
            }
        });

        // Actualizar el slug cuando se edita el título de la novela
        static::updating(function ($novel) {
            if ($novel->isDirty('title')) { // Verificar si el campo 'title' ha cambiado
                $slug = Str::slug($novel->title);
                $code = mt_rand(1000, 9999); // Código aleatorio de 4 dígitos
                $novel->slug = "{$slug}-{$code}";

                // Verificar si el slug ya existe y generar uno nuevo si es necesario
                while (Novel::where('slug', $novel->slug)
                    ->where('id', '!=', $novel->id) // Excluir el registro actual
                    ->exists()
                ) {
                    $code = mt_rand(1000, 9999); // Generar un nuevo código
                    $novel->slug = "{$slug}-{$code}";
                }
            }
        });
    }

    // Relación con el usuario que creó la novela
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function latestChapter()
    {
        return $this->hasOne(Chapter::class)->latestOfMany();
    }

    // Relación con la novela que pertenece el capitulo
    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    // Relación con las visitas
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'novel_id', 'user_id')->withTimestamps();
    }
}


/* // Generar automáticamente el slug antes de crear el registro
protected static function boot()
{
    parent::boot();

    static::creating(function ($novel) {
        // Generar el slug a partir del título
        $slug = Str::slug($novel->title);
        $originalSlug = $slug;
        $count = 1;

        // Verificar si el slug ya existe y generar uno nuevo si es necesario
        while (Novel::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        // Asignar el slug único al modelo
        $novel->slug = $slug;
    });
} */