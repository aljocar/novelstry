<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $fillable = [
        'content',
        'user_id',
    ];

    // Relación con el usuario que hizo el comentario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con la novela a la que pertenece el comentario
    public function commentable()
    {
        return $this->morphTo();
    }
}
