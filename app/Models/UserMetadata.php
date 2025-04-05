<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMetadata extends Model
{
    use HasFactory;

    protected $table = 'user_metadata';

    protected $fillable = [
        'user_id', 
        'real_name', 
        'address', 
        'contact',
        'bibliography', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
