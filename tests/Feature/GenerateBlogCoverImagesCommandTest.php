<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GenerateBlogCoverImagesCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_generates_blog_cover_image_and_updates_post(): void
    {
        Storage::fake('public');
        config([
            'services.openai.key' => 'test-openai-key',
            'services.openai.image_model' => 'gpt-image-1.5',
            'services.openai.image_size' => '1536x1024',
            'services.openai.image_quality' => 'medium',
            'services.openai.image_format' => 'webp',
        ]);

        Http::fake([
            'api.openai.com/v1/images/generations' => Http::response([
                'data' => [
                    ['b64_json' => base64_encode('fake-webp-image')],
                ],
            ]),
        ]);

        $post = BlogPost::factory()->create([
            'title' => 'Audi Q3 ili BMW X3: šta kupiti u Srbiji',
            'slug' => 'audi-q3-ili-bmw-x3',
            'category' => 'Poređenje modela',
            'cover_image_path' => 'blog/trendovi/audi-q3-ili-bmw-x3-cover.svg',
            'cover_image_alt' => null,
            'tags' => ['Audi Q3', 'BMW X3', 'polovnjaci'],
        ]);

        $this->artisan('blog:generate-covers', [
            '--slug' => [$post->slug],
        ])->assertSuccessful();

        $post->refresh();

        $this->assertSame('blog/generated/'.$post->slug.'.webp', $post->cover_image_path);
        $this->assertSame($post->title, $post->cover_image_alt);
        Storage::disk('public')->assertExists($post->cover_image_path);
        $this->assertSame('fake-webp-image', Storage::disk('public')->get($post->cover_image_path));

        Http::assertSent(function ($request) use ($post) {
            return $request->url() === 'https://api.openai.com/v1/images/generations'
                && $request->hasHeader('Authorization', 'Bearer test-openai-key')
                && $request['model'] === 'gpt-image-1.5'
                && $request['size'] === '1536x1024'
                && $request['quality'] === 'medium'
                && $request['output_format'] === 'webp'
                && str_contains($request['prompt'], $post->title)
                && str_contains($request['prompt'], 'do not show exact brand logos')
                && str_contains($request['prompt'], 'text overlays');
        });
    }

    public function test_dry_run_does_not_call_openai_or_update_posts(): void
    {
        Storage::fake('public');
        Http::fake();

        $post = BlogPost::factory()->create([
            'slug' => 'dry-run-post',
            'cover_image_path' => null,
        ]);

        $this->artisan('blog:generate-covers', [
            '--slug' => [$post->slug],
            '--dry-run' => true,
        ])->assertSuccessful();

        $this->assertNull($post->fresh()->cover_image_path);
        Http::assertNothingSent();
    }

    public function test_command_requires_openai_key_when_generation_runs(): void
    {
        Storage::fake('public');
        config(['services.openai.key' => null]);
        Http::fake();

        $post = BlogPost::factory()->create([
            'slug' => 'missing-key-post',
            'cover_image_path' => null,
        ]);

        $this->artisan('blog:generate-covers', [
            '--slug' => [$post->slug],
        ])->assertFailed();

        $this->assertNull($post->fresh()->cover_image_path);
        Http::assertNothingSent();
    }

    public function test_existing_non_generated_cover_is_skipped_without_force(): void
    {
        Storage::fake('public');
        config(['services.openai.key' => 'test-openai-key']);
        Http::fake();

        $post = BlogPost::factory()->create([
            'slug' => 'existing-cover-post',
            'cover_image_path' => 'blog/generated/existing-cover-post.webp',
        ]);

        $this->artisan('blog:generate-covers', [
            '--slug' => [$post->slug],
        ])->assertSuccessful();

        $this->assertSame('blog/generated/existing-cover-post.webp', $post->fresh()->cover_image_path);
        Http::assertNothingSent();
    }
}
