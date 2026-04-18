<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Database\Seeders\TrendBlogPostSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TrendBlogPostSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_trend_blog_post_seeder_creates_five_idempotent_comparison_articles(): void
    {
        Storage::fake('public');

        $this->seed(TrendBlogPostSeeder::class);
        $this->seed(TrendBlogPostSeeder::class);

        $posts = BlogPost::query()
            ->where('category', 'Poređenje modela')
            ->get();

        $this->assertCount(5, $posts);
        $this->assertSame(1, $posts->where('is_featured', true)->count());
        $this->assertTrue($posts->contains('slug', 'golf-7-ili-audi-a3-sta-je-pametnija-kupovina-u-srbiji'));
        $this->assertTrue($posts->contains('slug', 'bmw-x3-audi-q5-ili-audi-q3-koji-premium-suv-ima-najvise-smisla'));

        $posts->each(function (BlogPost $post) {
            $this->assertNotEmpty($post->cover_image_path);
            Storage::disk('public')->assertExists($post->cover_image_path);
        });
    }
}
