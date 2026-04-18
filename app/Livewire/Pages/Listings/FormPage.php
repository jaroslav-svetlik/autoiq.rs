<?php

namespace App\Livewire\Pages\Listings;

use App\Enums\ListingStatus;
use App\Enums\SellerType;
use App\Livewire\Concerns\ThrottlesRequests;
use App\Livewire\Pages\PageComponent;
use App\Models\Listing;
use App\Models\ListingImage;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class FormPage extends PageComponent
{
    use ThrottlesRequests;
    use WithFileUploads;

    protected const MAX_IMAGES = 20;
    protected const MAX_IMAGE_SIZE_KB = 1024;

    public ?Listing $listing = null;
    public string $titleInput = '';
    public string $brand = '';
    public string $model = '';
    public string $year = '';
    public string $price = '';
    public string $mileage = '';
    public string $fuelType = '';
    public string $transmission = '';
    public string $city = '';
    public string $description = '';
    public string $sellerType = 'private';
    public array $equipment = [];
    public array $newImages = [];

    public function mount(?Listing $listing = null): void
    {
        if ($listing) {
            abort_unless(
                auth()->id() === $listing->user_id || auth()->user()?->isAdmin(),
                403,
            );

            $this->listing = $listing->load('images', 'equipmentItems');
            $this->titleInput = $listing->title;
            $this->brand = $listing->brand;
            $this->model = $listing->model;
            $this->year = (string) $listing->year;
            $this->price = (string) $listing->price;
            $this->mileage = (string) $listing->mileage;
            $this->fuelType = $listing->fuel_type?->value ?? '';
            $this->transmission = $listing->transmission?->value ?? '';
            $this->city = $listing->city;
            $this->description = $listing->description;
            $this->sellerType = $listing->seller_type?->value ?? 'private';
            $this->equipment = $listing->equipmentKeys()->all();
        } elseif (auth()->user()?->isDealer()) {
            $this->sellerType = SellerType::Dealer->value;
        }
    }

    public function save(): void
    {
        $this->throttle('listing-save', 10, 60);

        $validated = $this->validate($this->rules(), $this->messages());

        if ($validated['sellerType'] === SellerType::Dealer->value && ! auth()->user()?->isDealer()) {
            $this->addError('sellerType', 'Samo diler može objaviti oglas kao diler.');

            return;
        }

        $listing = $this->listing ?? new Listing();

        $listing->fill([
            'user_id' => auth()->id(),
            'title' => $validated['titleInput'],
            'brand' => $validated['brand'],
            'model' => $validated['model'],
            'year' => (int) $validated['year'],
            'price' => (int) $validated['price'],
            'mileage' => (int) $validated['mileage'],
            'fuel_type' => $validated['fuelType'],
            'transmission' => $validated['transmission'],
            'city' => $validated['city'],
            'description' => $validated['description'],
            'seller_type' => $validated['sellerType'],
            'status' => $listing->status ?? ListingStatus::Published,
        ]);

        $listing->save();
        $listing->syncEquipment($validated['equipment'] ?? []);

        $this->storeImages($listing);

        session()->flash('status', $this->listing ? 'Oglas je uspešno ažuriran.' : 'Oglas je uspešno objavljen.');

        $this->redirectRoute('listings.show', $listing, navigate: true);
    }

    public function updatedNewImages(): void
    {
        $this->validate($this->imageRules(), $this->messages());
    }

    public function deleteImage(int $imageId): void
    {
        abort_unless($this->listing, 404);

        $image = $this->listing->images()->findOrFail($imageId);

        if (! str_starts_with($image->path, 'http')) {
            Storage::disk('public')->delete($image->path);
        }

        $image->delete();
        $this->listing->refresh();
        $this->resetErrorBag('newImages');
    }

    public function removeNewImage(int $index): void
    {
        unset($this->newImages[$index]);
        $this->newImages = array_values($this->newImages);
        $this->resetErrorBag('newImages');
    }

    protected function storeImages(Listing $listing): void
    {
        $sortOrder = (int) $listing->images()->max('sort_order') + 1;

        foreach ($this->newImages as $image) {
            if (! $image instanceof TemporaryUploadedFile) {
                continue;
            }

            $path = $image->store('listings', 'public');

            $listing->images()->create([
                'path' => $path,
                'alt_text' => $listing->title,
                'sort_order' => $sortOrder,
            ]);

            $sortOrder++;
        }

        $this->newImages = [];
    }

    protected function rules(): array
    {
        return [
            'titleInput' => ['required', 'string', 'min:8', 'max:120'],
            'brand' => ['required', 'string', 'max:60'],
            'model' => ['required', 'string', 'max:80'],
            'year' => ['required', 'integer', 'min:1990', 'max:'.now()->year],
            'price' => ['required', 'integer', 'min:500', 'max:500000'],
            'mileage' => ['required', 'integer', 'min:0', 'max:1000000'],
            'fuelType' => ['required', 'in:'.implode(',', array_keys(config('autoiq.fuel_types')))],
            'transmission' => ['required', 'in:'.implode(',', array_keys(config('autoiq.transmission_types')))],
            'city' => ['required', 'string', 'max:80'],
            'description' => ['required', 'string', 'min:30', 'max:5000'],
            'sellerType' => ['required', 'in:private,dealer'],
            'equipment' => ['nullable', 'array'],
            'equipment.*' => ['string', Rule::in(array_keys(Listing::equipmentKeyMap()))],
            ...$this->imageRules(),
        ];
    }

    protected function imageRules(): array
    {
        return [
            'newImages' => [
                'nullable',
                'array',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ($this->totalImageCount() > self::MAX_IMAGES) {
                        $fail("Možete imati najviše ".self::MAX_IMAGES." slika po oglasu.");
                    }
                },
            ],
            'newImages.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:'.self::MAX_IMAGE_SIZE_KB],
        ];
    }

    protected function totalImageCount(): int
    {
        return $this->existingImageCount() + count($this->newImages);
    }

    protected function existingImageCount(): int
    {
        if (! $this->listing) {
            return 0;
        }

        return $this->listing->images->count();
    }

    protected function messages(): array
    {
        return [
            'titleInput.required' => 'Naslov oglasa je obavezan.',
            'brand.required' => 'Unesite marku.',
            'model.required' => 'Unesite model.',
            'year.required' => 'Unesite godište.',
            'price.required' => 'Unesite cenu.',
            'mileage.required' => 'Unesite kilometražu.',
            'description.min' => 'Opis treba da sadrži makar 30 karaktera.',
            'equipment.*.in' => 'Izabrana stavka opreme nije dostupna.',
            'newImages.array' => 'Otpremanje fotografija mora biti u ispravnom formatu.',
            'newImages.*.image' => 'Svaki fajl mora biti slika.',
            'newImages.*.mimes' => 'Dozvoljeni formati su JPG, PNG i WEBP.',
            'newImages.*.max' => 'Svaka slika može imati najviše 1 MB.',
        ];
    }

    protected function title(): string
    {
        return $this->listing ? 'Izmena oglasa | AutoIQ' : 'Novi oglas | AutoIQ';
    }

    protected function meta(): array
    {
        return [
            ...parent::meta(),
            'robots' => 'noindex,nofollow',
        ];
    }

    public function render(): View
    {
        return $this->page(view('livewire.pages.listings.form-page', [
            'cities' => config('autoiq.cities'),
            'fuelTypes' => config('autoiq.fuel_types'),
            'transmissionTypes' => config('autoiq.transmission_types'),
            'sellerTypes' => config('autoiq.seller_types'),
            'equipmentCatalog' => Listing::equipmentCatalog(),
        ]));
    }
}
