<?php

namespace App\Notifications;

use App\Models\Listing;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PriceDropAlert extends Notification
{
    use Queueable;

    public function __construct(
        public Listing $listing,
        public int $oldPrice,
        public string $context = 'favorite',
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $difference = max($this->oldPrice - $this->listing->price, 0);
        $percentage = $this->oldPrice > 0
            ? round(($difference / $this->oldPrice) * 100, 1)
            : 0;

        return [
            'type' => 'price_drop',
            'title' => 'Cena je pala',
            'message' => "{$this->listing->brand} {$this->listing->model} je sada jeftiniji za ".number_format($difference, 0, ',', '.')." € ({$percentage}%).",
            'url' => route('listings.show', $this->listing),
            'listing_id' => $this->listing->id,
            'context' => $this->context,
        ];
    }
}
