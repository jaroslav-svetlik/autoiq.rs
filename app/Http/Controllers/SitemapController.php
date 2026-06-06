<?php

namespace App\Http\Controllers;

use App\Enums\ListingStatus;
use App\Models\BlogPost;
use App\Models\DealerProfile;
use App\Models\Listing;
use App\Support\Seo\VehicleLandingPages;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use XMLWriter;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        return response($this->xml($this->urls()), 200)
            ->header('Cache-Control', 'public, max-age=3600')
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * @return Collection<int, array{loc: string, lastmod?: string|null}>
     */
    protected function urls(): Collection
    {
        $latestBlogUpdate = BlogPost::query()->published()->max('updated_at');
        $latestListingUpdate = Listing::query()->where('status', ListingStatus::Published)->max('updated_at');
        $latestDealerUpdate = DealerProfile::query()->max('updated_at');

        return collect([
            [
                'loc' => route('home'),
                'lastmod' => $this->latestLastmod($latestBlogUpdate, $latestListingUpdate),
            ],
            [
                'loc' => route('blog.index'),
                'lastmod' => $this->lastmod($latestBlogUpdate),
            ],
            [
                'loc' => route('listings.index'),
                'lastmod' => $this->lastmod($latestListingUpdate),
            ],
            [
                'loc' => route('contact'),
            ],
        ])
            ->merge(BlogPost::query()
                ->published()
                ->latest('updated_at')
                ->get()
                ->map(fn (BlogPost $blogPost) => [
                    'loc' => route('blog.show', $blogPost),
                    'lastmod' => $this->lastmod($blogPost->updated_at),
                ]))
            ->merge(Listing::query()
                ->where('status', ListingStatus::Published)
                ->latest('updated_at')
                ->get()
                ->map(fn (Listing $listing) => [
                    'loc' => route('listings.show', $listing),
                    'lastmod' => $this->lastmod($listing->updated_at),
                ]))
            ->merge(collect(VehicleLandingPages::all())
                ->map(fn (array $landingPage) => [
                    'loc' => route('listings.model', VehicleLandingPages::routeParameters($landingPage['brand'], $landingPage['model'])),
                    'lastmod' => $this->latestLastmod($latestListingUpdate, $latestBlogUpdate),
                ]))
            ->merge(DealerProfile::query()
                ->latest('updated_at')
                ->get()
                ->map(fn (DealerProfile $dealerProfile) => [
                    'loc' => route('dealers.show', $dealerProfile),
                    'lastmod' => $this->lastmod($dealerProfile->updated_at ?: $latestDealerUpdate),
                ]))
            ->filter(fn (array $entry) => filled($entry['loc']))
            ->unique('loc')
            ->values();
    }

    /**
     * @param  Collection<int, array{loc: string, lastmod?: string|null}>  $urls
     */
    protected function xml(Collection $urls): string
    {
        $writer = new XMLWriter;
        $writer->openMemory();
        $writer->startDocument('1.0', 'UTF-8');
        $writer->startElement('urlset');
        $writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        foreach ($urls as $entry) {
            $writer->startElement('url');
            $writer->writeElement('loc', $entry['loc']);

            if (! empty($entry['lastmod'])) {
                $writer->writeElement('lastmod', $entry['lastmod']);
            }

            $writer->endElement();
        }

        $writer->endElement();
        $writer->endDocument();

        return $writer->outputMemory();
    }

    private function latestLastmod(mixed ...$values): ?string
    {
        return collect($values)
            ->map(fn (mixed $value) => $value ? Carbon::parse($value) : null)
            ->filter()
            ->max()
            ?->toAtomString();
    }

    private function lastmod(mixed $value): ?string
    {
        return $value ? Carbon::parse($value)->toAtomString() : null;
    }
}
