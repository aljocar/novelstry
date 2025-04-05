<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Chapter;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {        
        User::factory()->create([
            'username' => 'aljocar',
            'password' => '1234',
            'user_type' => '2', //Usuario Administrador
            'profile_image' => 'defaults/default_avatar.jpg',
        ]);

        User::factory(2)->create();

        // Llama al NovelSeeder
        $this->call(NovelSeeder::class);

        // Crear 10 capítulos de prueba
        Chapter::factory()->count(50)->create();

        Category::create([
            'name' => 'Acción',
            'slug' => Str::slug('Acción'),
        ]);
    }
}
