<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_index_displays_featured_article_and_latest_posts(): void
    {
        $featured = BlogPost::factory()->featured()->create([
            'title' => 'Kako da kupiš bolji polovnjak',
            'category' => 'Kupovina polovnjaka',
            'published_at' => now()->subDays(3),
        ]);

        $latest = BlogPost::factory()->create([
            'title' => 'Najvažniji signali kada cena pada',
            'category' => 'Pregovaranje',
            'published_at' => now()->subDay(),
        ]);

        $this->get(route('blog.index'))
            ->assertOk()
            ->assertSee('AutoIQ Blog')
            ->assertSee($featured->title)
            ->assertSee($latest->title);
    }

    public function test_google_tag_is_not_rendered_outside_production(): void
    {
        $this->get(route('blog.index'))
            ->assertOk()
            ->assertDontSee('googletagmanager.com/gtag/js', false)
            ->assertDontSee('G-1TMX1CMMRS', false);
    }

    public function test_blog_index_can_filter_posts_by_category(): void
    {
        $matching = BlogPost::factory()->create([
            'title' => 'Kako čitati tržišni prosek',
            'category' => 'Analiza tržišta',
            'published_at' => now()->subDays(2),
        ]);

        $other = BlogPost::factory()->create([
            'title' => 'Kako pregovarati sa prodavcem',
            'category' => 'Pregovaranje',
            'published_at' => now()->subDays(2),
        ]);

        $this->get(route('blog.index', ['tema' => 'Analiza tržišta']))
            ->assertOk()
            ->assertSee($matching->title)
            ->assertDontSee($other->title);
    }

    public function test_blog_show_page_renders_article_content_and_related_posts(): void
    {
        $post = BlogPost::factory()->create([
            'title' => 'Dizel ili benzin za gradsku vožnju',
            'category' => 'Troškovi i održavanje',
            'content' => "Prvi pasus o gradskoj vožnji.\n\nDrugi pasus o troškovima i održavanju.",
            'highlights' => ['Gledaj kilometražu i režim vožnje.'],
            'tags' => ['dizel', 'benzin'],
            'published_at' => now()->subDays(2),
        ]);

        $related = BlogPost::factory()->create([
            'title' => 'Koliko vredi niža potrošnja na otvorenom putu',
            'category' => 'Troškovi i održavanje',
            'published_at' => now()->subDay(),
        ]);

        $this->get(route('blog.show', $post))
            ->assertOk()
            ->assertSee($post->title)
            ->assertSee('Ključne poruke')
            ->assertSee('Gledaj kilometražu i režim vožnje.')
            ->assertSee($related->title)
            ->assertSee('aspect-[3/2]', false)
            ->assertSee('object-contain', false);
    }

    public function test_home_page_and_sitemap_include_blog_content(): void
    {
        $post = BlogPost::factory()->create([
            'title' => 'Kako uporediti oglase istog modela',
            'published_at' => now()->subDay(),
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Praktični vodiči za pametniju kupovinu')
            ->assertSee($post->title);

        $this->get(route('sitemap'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('<?xml version="1.0" encoding="UTF-8"?>', false)
            ->assertSee(route('blog.index'))
            ->assertSee(route('contact'))
            ->assertSee(route('blog.show', $post));
    }
}
