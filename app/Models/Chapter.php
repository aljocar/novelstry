<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Chapter extends Model
{
    use HasFactory;

    protected $table = 'chapters';

    protected $fillable = [
        'title',       // Título del capítulo
        'slug',
        'content',    // Contenido del capítulo
        'novel_id',   // ID de la novela a la que pertenece el capítulo
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        // Generar automáticamente el slug antes de crear el registro
        static::creating(function ($chapter) {
            $slug = Str::slug($chapter->title);
            $code = mt_rand(1000, 9999); // Código aleatorio de 4 dígitos
            $chapter->slug = "{$slug}-{$code}";

            // Verificar si el slug ya existe y generar uno nuevo si es necesario
            while (Chapter::where('slug', $chapter->slug)
                ->where('novel_id', $chapter->novel_id)
                ->exists()
            ) {
                $code = mt_rand(1000, 9999); // Generar un nuevo código
                $chapter->slug = "{$slug}-{$code}";
            }
        });

        // Actualizar el slug cuando se edita el título del capítulo
        static::updating(function ($chapter) {
            if ($chapter->isDirty('title')) { // Verificar si el campo 'title' ha cambiado
                $slug = Str::slug($chapter->title);
                $code = mt_rand(1000, 9999); // Código aleatorio de 4 dígitos
                $chapter->slug = "{$slug}-{$code}";

                // Verificar si el slug ya existe y generar uno nuevo si es necesario
                while (Chapter::where('slug', $chapter->slug)
                    ->where('novel_id', $chapter->novel_id)
                    ->where('id', '!=', $chapter->id) // Excluir el registro actual
                    ->exists()
                ) {
                    $code = mt_rand(1000, 9999); // Generar un nuevo código
                    $chapter->slug = "{$slug}-{$code}";
                }
            }
        });
    }

    public function novel()
    {
        return $this->belongsTo(Novel::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
