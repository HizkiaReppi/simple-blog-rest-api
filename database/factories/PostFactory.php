<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(mt_rand(2,4));
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => collect(fake()->paragraphs(mt_rand(20,30)))
                ->map(fn ($p) => "<p>$p</p>")
                ->implode(''),
            'image' => fake()->imageUrl(),
            'user_id' => mt_rand(1,2),
            'category_id' => mt_rand(1,2)
        ];
    }
}
