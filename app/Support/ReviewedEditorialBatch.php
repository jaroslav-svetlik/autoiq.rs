<?php

namespace App\Support;

use Database\Seeders\TrendBlogPostSeeder;
use InvalidArgumentException;
use ReflectionMethod;
use RuntimeException;

final class ReviewedEditorialBatch
{
    public static function load(string $date): array
    {
        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/D', $date)
            || ! checkdate((int) substr($date, 5, 2), (int) substr($date, 8, 2), (int) substr($date, 0, 4))) {
            throw new InvalidArgumentException('Expected a valid YYYY-MM-DD editorial date.');
        }
        $file = database_path('seeders/data/'.$date.'.php');
        if (! is_file($file)) {
            throw new RuntimeException('Prepare and review the five-article source batch first: '.$file);
        }
        $posts = array_map(fn (array $post) => $post + [
            'author_name' => 'AutoIQ redakcija', 'is_featured' => false,
        ], require $file);
        EditorialBatchValidator::validate($posts);
        $seeder = new TrendBlogPostSeeder;
        $transform = new ReflectionMethod($seeder, 'professionalizeEditorialVoice');
        foreach ($posts as $post) {
            if (! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/D', $post['slug'])) {
                throw new RuntimeException('Unsafe editorial slug: '.$post['slug']);
            }
            if ($transform->invoke($seeder, $post) !== $post) {
                throw new RuntimeException('Legacy transformation changes reviewed text: '.$post['slug']);
            }
        }

        return $posts;
    }
}
