<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Novel>
 */
class NovelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = $this->faker->sentence(2); // Título de 2 palabras

        return [
            'title' => $title,
            'slug' => Str::slug($title), // Slug basado en el título
            'synopsis' => $this->faker->paragraph(3), // Sinopsis de 3 párrafos
            'cover_image' => 'defaults/default_cover.jpg',
            'user_id' => 1,
        ];
    }
}
