<?php

namespace App\Notifications;

use App\Models\Listing;
use App\Models\SavedSearch;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewListingMatchAlert extends Notification
{
    use Queueable;

    public function __construct(
        public Listing $listing,
        public SavedSearch $savedSearch,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_listing_match',
            'title' => 'Novi oglas odgovara vašoj pretrazi',
            'message' => "{$this->listing->brand} {$this->listing->model} ({$this->listing->year}) odgovara pretrazi \"{$this->savedSearch->name}\".",
            'url' => route('listings.show', $this->listing),
            'listing_id' => $this->listing->id,
            'saved_search_id' => $this->savedSearch->id,
        ];
    }
}
