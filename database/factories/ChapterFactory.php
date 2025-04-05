<?php

namespace Database\Factories;

use App\Models\Novel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chapter>
 */
class ChapterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(3); // Título de 3 palabras

        return [
            'title' => $title, // Título aleatorio
            'content' => $this->faker->paragraphs(5, true), // Contenido aleatorio (5 párrafos)
            'slug' => Str::slug($title), // Slug basado en el título
            'novel_id' => 1,
            //'novel_id' => Novel::factory(), // Relación con una novela (crea una novela automáticamente)
        ];
    }
}
