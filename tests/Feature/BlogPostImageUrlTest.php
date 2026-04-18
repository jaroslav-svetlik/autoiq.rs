<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BlogPostImageUrlTest extends TestCase
{
    public function test_local_blog_cover_urls_include_file_version_for_cache_busting(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('blog/generated/test-cover.webp', 'optimized-image');

        $post = BlogPost::factory()->make([
            'cover_image_path' => 'blog/generated/test-cover.webp',
        ]);

        $this->assertMatchesRegularExpression(
            '#^/storage/blog/generated/test-cover\.webp\?v=\d+$#',
            $post->coverImageUrl(),
        );
    }
}
