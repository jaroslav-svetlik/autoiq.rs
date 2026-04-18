<?php

namespace App\Http\Controllers;

use App\Enums\ListingStatus;
use App\Models\BlogPost;
use App\Models\DealerProfile;
use App\Models\Listing;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        return response()
            ->view('sitemap', [
                'staticUrls' => [
                    route('home'),
                    route('blog.index'),
                    route('listings.index'),
                ],
                'blogUrls' => BlogPost::query()
                    ->published()
                    ->latest('updated_at')
                    ->get()
                    ->map(fn (BlogPost $blogPost) => route('blog.show', $blogPost)),
                'listingUrls' => Listing::query()
                    ->where('status', ListingStatus::Published)
                    ->latest('updated_at')
                    ->get()
                    ->map(fn (Listing $listing) => route('listings.show', $listing)),
                'dealerUrls' => DealerProfile::query()
                    ->latest('updated_at')
                    ->get()
                    ->map(fn (DealerProfile $dealerProfile) => route('dealers.show', $dealerProfile)),
            ])
            ->header('Content-Type', 'application/xml');
    }
}
