<?php

namespace App\Http\Controllers;

use App\Enums\ListingStatus;
use App\Models\BlogPost;
use App\Models\DealerProfile;
use App\Models\Listing;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use XMLWriter;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        return response($this->xml($this->urls()), 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * @return Collection<int, string>
     */
    protected function urls(): Collection
    {
        return collect([
            route('home'),
            route('blog.index'),
            route('listings.index'),
        ])
            ->merge(BlogPost::query()
                ->published()
                ->latest('updated_at')
                ->get()
                ->map(fn (BlogPost $blogPost) => route('blog.show', $blogPost)))
            ->merge(Listing::query()
                ->where('status', ListingStatus::Published)
                ->latest('updated_at')
                ->get()
                ->map(fn (Listing $listing) => route('listings.show', $listing)))
            ->merge(DealerProfile::query()
                ->latest('updated_at')
                ->get()
                ->map(fn (DealerProfile $dealerProfile) => route('dealers.show', $dealerProfile)))
            ->filter()
            ->unique()
            ->values();
    }

    /**
     * @param  Collection<int, string>  $urls
     */
    protected function xml(Collection $urls): string
    {
        $writer = new XMLWriter;
        $writer->openMemory();
        $writer->startDocument('1.0', 'UTF-8');
        $writer->startElement('urlset');
        $writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        foreach ($urls as $url) {
            $writer->startElement('url');
            $writer->writeElement('loc', $url);
            $writer->endElement();
        }

        $writer->endElement();
        $writer->endDocument();

        return $writer->outputMemory();
    }
}
