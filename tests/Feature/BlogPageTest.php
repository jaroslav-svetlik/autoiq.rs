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
            ->assertSee('property="og:image" content="'.url($post->coverImageUrl(absolute: true)).'"', false)
            ->assertSee('"dateModified"', false)
            ->assertSee('"BreadcrumbList"', false)
            ->assertSee('aspect-[3/2]', false)
            ->assertSee('object-contain', false);
    }

    public function test_blog_show_page_renders_contextual_links_market_ctas_and_article_topics(): void
    {
        $post = BlogPost::factory()->create([
            'title' => 'Golf 7 dizel automatik: kako proveriti realnu cenu',
            'category' => 'Poređenje modela',
            'content' => "Prvi pasus o Golfu.\n\nDrugi pasus o ceni.\n\nTreći pasus o menjaču.",
            'tags' => ['Golf 7', 'Volkswagen', 'dizel', 'automatik'],
            'published_at' => now()->subDays(2),
        ]);

        $related = BlogPost::factory()->create([
            'title' => 'Audi A3 ili Golf 7: šta je pametnija kupovina',
            'category' => 'Poređenje modela',
            'tags' => ['Golf 7', 'Audi A3'],
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('blog.show', $post));

        $response
            ->assertOk()
            ->assertSee('Povezani vodiči')
            ->assertSee($related->title)
            ->assertSee(route('blog.show', $related), false)
            ->assertSee('Pretraga oglasa')
            ->assertSee('Pogledaj Volkswagen Golf 7 oglase')
            ->assertSee('brand=Volkswagen', false)
            ->assertSee('model=Golf%207', false)
            ->assertSee('Otvori celu temu')
            ->assertSee('"about"', false)
            ->assertSee('"mentions"', false)
            ->assertSee('"isPartOf"', false);
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
            ->assertSee(route('blog.show', $post))
            ->assertSee('<lastmod>'.$post->updated_at->toAtomString().'</lastmod>', false)
            ->assertHeaderMissing('Set-Cookie');
    }

    public function test_robots_txt_points_to_sitemap(): void
    {
        $this->assertStringContainsString(
            'Sitemap: https://autoiq.rs/sitemap.xml',
            file_get_contents(public_path('robots.txt')),
        );
    }
}
