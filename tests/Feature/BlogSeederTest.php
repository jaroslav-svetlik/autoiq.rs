<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BlogSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_demo_blog_posts_with_local_cover_images(): void
    {
        Storage::fake('public');

        $this->seed(DatabaseSeeder::class);

        $posts = BlogPost::query()->get();

        $this->assertCount(4, $posts);
        $this->assertTrue($posts->every(fn (BlogPost $post) => filled($post->cover_image_path)));

        $posts->each(fn (BlogPost $post) => Storage::disk('public')->assertExists($post->cover_image_path));
    }
}
