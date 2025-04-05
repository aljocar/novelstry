<?php

namespace Database\Seeders;

use App\Models\Novel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NovelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear otro registro manualmente
        $novel = new Novel();
        $novel->title = '1984';
        $novel->slug = '1984-2938';
        $novel->synopsis = 'Una distopía sobre un régimen totalitario que controla cada aspecto de la vida.';
        $novel->cover_image = 'defaults/default_cover.jpg';
        $novel->user_id = '1';
        $novel->save();

        // Crear 10 registros de prueba usando Factory
        Novel::factory(8)->create();
    }
}
