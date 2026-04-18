<?php

namespace Database\Factories;

use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<BlogPost>
 */
class BlogPostFactory extends Factory
{
    protected $model = BlogPost::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(6);
        $content = collect(fake()->paragraphs(5))
            ->implode("\n\n");

        return [
            'title' => Str::title($title),
            'category' => fake()->randomElement([
                'Analiza tržišta',
                'Kupovina polovnjaka',
                'Troškovi i održavanje',
            ]),
            'author_name' => 'AutoIQ redakcija',
            'excerpt' => fake()->sentence(18),
            'content' => $content,
            'highlights' => fake()->randomElements([
                'Proveri odstupanje cene od proseka.',
                'Uporedi kilometražu sa godištem i servisnom istorijom.',
                'Traži više oglasa istog modela pre odluke.',
            ], 3),
            'tags' => fake()->randomElements([
                'cene',
                'polovnjaci',
                'Srbija',
                'pregovaranje',
                'dileri',
            ], 3),
            'meta_title' => null,
            'meta_description' => null,
            'reading_time_minutes' => 4,
            'is_featured' => false,
            'published_at' => now()->subDays(fake()->numberBetween(1, 20)),
        ];
    }

    public function featured(): static
    {
        return $this->state(fn () => [
            'is_featured' => true,
        ]);
    }
}
