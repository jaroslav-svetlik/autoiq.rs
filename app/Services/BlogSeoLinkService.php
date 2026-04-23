<?php

namespace App\Services;

use App\Enums\FuelType;
use App\Enums\TransmissionType;
use App\Models\BlogPost;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BlogSeoLinkService
{
    /**
     * @return Collection<int, BlogPost>
     */
    public function relatedPosts(BlogPost $post, int $limit = 3): Collection
    {
        $candidates = BlogPost::query()
            ->published()
            ->whereKeyNot($post->getKey())
            ->latest('published_at')
            ->get();

        if ($candidates->isEmpty()) {
            return collect();
        }

        $sourceTerms = $this->termsFor($post);
        $sourceTags = $this->normalizedTags($post);

        $scored = $candidates
            ->map(fn (BlogPost $candidate) => [
                'post' => $candidate,
                'score' => $this->scorePost($post, $candidate, $sourceTerms, $sourceTags),
            ])
            ->filter(fn (array $item) => $item['score'] > 0)
            ->sortByDesc(fn (array $item) => [
                $item['score'],
                optional($item['post']->published_at)->timestamp ?? 0,
                $item['post']->id,
            ])
            ->pluck('post')
            ->values();

        if ($scored->count() < $limit) {
            $fallback = $candidates
                ->reject(fn (BlogPost $candidate) => $scored->contains(fn (BlogPost $post) => $post->is($candidate)))
                ->take($limit - $scored->count());

            $scored = $scored->merge($fallback)->values();
        }

        return $scored->take($limit)->values();
    }

    /**
     * @return Collection<int, array{title: string, description: string, url: string, category: ?string}>
     */
    public function contextualBlogLinks(BlogPost $post, int $limit = 3): Collection
    {
        return $this->relatedPosts($post, $limit)
            ->map(fn (BlogPost $relatedPost) => [
                'title' => $relatedPost->title,
                'description' => $relatedPost->excerptText(),
                'url' => route('blog.show', $relatedPost),
                'category' => $relatedPost->category,
            ])
            ->values();
    }

    /**
     * @return Collection<int, array{label: string, description: string, url: string}>
     */
    public function marketLinks(BlogPost $post, int $limit = 3): Collection
    {
        $text = $this->normalizedSearchText($post);
        $links = collect();

        foreach ($this->vehicleRules() as $rule) {
            if (! $this->containsAny($text, $rule['needles'])) {
                continue;
            }

            $links->push($this->listingLink(
                label: $rule['label'],
                description: $rule['description'],
                filters: $rule['filters'],
            ));
        }

        if (Str::contains($text, ['5000', '5 000', '5.000'])) {
            $links->push($this->listingLink(
                label: 'Pretraži automobile do 5.000 €',
                description: 'Uporedi oglase u nižem budžetu i proveri gde prva ulaganja menjaju računicu.',
                filters: ['max_price' => 5000, 'sort' => 'best'],
            ));
        }

        if (Str::contains($text, ['10000', '10 000', '10.000'])) {
            $links->push($this->listingLink(
                label: 'Pretraži automobile do 10.000 €',
                description: 'Pogledaj aktuelne oglase u budžetu gde izbor motora i stanja najviše utiče na vrednost.',
                filters: ['max_price' => 10000, 'sort' => 'best'],
            ));
        }

        foreach ($this->fuelRules() as $needle => $rule) {
            if (! Str::contains($text, $needle)) {
                continue;
            }

            $links->push($this->listingLink(
                label: $rule['label'],
                description: $rule['description'],
                filters: $rule['filters'],
            ));
        }

        if (Str::contains($text, ['automatik', 'automatski', 'dsg'])) {
            $links->push($this->listingLink(
                label: 'Pogledaj oglase sa automatskim menjačem',
                description: 'Filtriraj automatike i uporedi ih po ceni, godištu, kilometraži i AutoIQ oceni.',
                filters: ['transmission' => TransmissionType::Automatic->value, 'sort' => 'best'],
            ));
        }

        if ($links->isEmpty()) {
            $links->push($this->listingLink(
                label: 'Pogledaj najbolje ocenjene oglase',
                description: 'Kreni od oglasa sortiranih po AutoIQ oceni i proveri koje ponude najviše odstupaju od proseka.',
                filters: ['sort' => 'best'],
            ));
        }

        $links->push($this->listingLink(
            label: 'Otvori sve auto oglase',
            description: 'Proširi pretragu na celo tržište i filtriraj vozila po budžetu, godištu, gorivu i opremi.',
            filters: [],
        ));

        return $links
            ->unique('url')
            ->take($limit)
            ->values();
    }

    /**
     * @return array<int, array{label: string, description: string, filters: array<string, mixed>, needles: array<int, string>}>
     */
    private function vehicleRules(): array
    {
        return [
            ['needles' => ['golf 7'], 'label' => 'Pogledaj Volkswagen Golf 7 oglase', 'description' => 'Uporedi Golf 7 oglase po ceni, kilometraži, opremi i AutoIQ oceni.', 'filters' => ['brand' => 'Volkswagen', 'model' => 'Golf 7', 'sort' => 'best']],
            ['needles' => ['audi a3'], 'label' => 'Pogledaj Audi A3 oglase', 'description' => 'Proveri da li premium kompakt zaista opravdava cenu u odnosu na stanje.', 'filters' => ['brand' => 'Audi', 'model' => 'A3', 'sort' => 'best']],
            ['needles' => ['audi a4'], 'label' => 'Pogledaj Audi A4 oglase', 'description' => 'Uporedi A4 ponude kroz cenu, kilometražu i istoriju promene cene.', 'filters' => ['brand' => 'Audi', 'model' => 'A4', 'sort' => 'best']],
            ['needles' => ['audi q3'], 'label' => 'Pogledaj Audi Q3 oglase', 'description' => 'Filtriraj Q3 oglase i proveri da li premium SUV cena prati realno stanje.', 'filters' => ['brand' => 'Audi', 'model' => 'Q3', 'sort' => 'best']],
            ['needles' => ['bmw 320d'], 'label' => 'Pogledaj BMW 320d oglase', 'description' => 'Uporedi 320d primerke po ceni, kilometraži i prodajnom riziku.', 'filters' => ['brand' => 'BMW', 'model' => '320d', 'sort' => 'best']],
            ['needles' => ['bmw x3'], 'label' => 'Pogledaj BMW X3 oglase', 'description' => 'Proveri premium SUV oglase i uporedi trošak održavanja sa cenom.', 'filters' => ['brand' => 'BMW', 'model' => 'X3', 'sort' => 'best']],
            ['needles' => ['bmw x1'], 'label' => 'Pogledaj BMW X1 oglase', 'description' => 'Kreni od kompaktnih BMW SUV oglasa i sortiraj ih po AutoIQ oceni.', 'filters' => ['brand' => 'BMW', 'model' => 'X1', 'sort' => 'best']],
            ['needles' => ['audi q5'], 'label' => 'Pogledaj Audi Q5 oglase', 'description' => 'Uporedi Q5 ponude sa drugim premium SUV modelima kroz stvarnu vrednost.', 'filters' => ['brand' => 'Audi', 'model' => 'Q5', 'sort' => 'best']],
            ['needles' => ['skoda octavia', 'skoda octavia', 'octavia'], 'label' => 'Pogledaj Škoda Octavia oglase', 'description' => 'Proveri Octavia ponude za porodičnu upotrebu, dizel računicu i servisnu istoriju.', 'filters' => ['brand' => 'Škoda', 'model' => 'Octavia', 'sort' => 'best']],
            ['needles' => ['skoda fabia', 'fabia'], 'label' => 'Pogledaj Škoda Fabia oglase', 'description' => 'Uporedi gradske Fabia oglase po budžetu, godištu i početnim ulaganjima.', 'filters' => ['brand' => 'Škoda', 'model' => 'Fabia', 'sort' => 'best']],
            ['needles' => ['skoda kodiaq', 'kodiaq'], 'label' => 'Pogledaj Škoda Kodiaq oglase', 'description' => 'Proveri porodične SUV oglase i uporedi ih sa alternativama iz iste klase.', 'filters' => ['brand' => 'Škoda', 'model' => 'Kodiaq', 'sort' => 'best']],
            ['needles' => ['opel astra'], 'label' => 'Pogledaj Opel Astra oglase', 'description' => 'Uporedi Astra oglase kroz cenu, motor, opremu i realna prva ulaganja.', 'filters' => ['brand' => 'Opel', 'model' => 'Astra', 'sort' => 'best']],
            ['needles' => ['opel corsa', 'corsa'], 'label' => 'Pogledaj Opel Corsa oglase', 'description' => 'Filtriraj Corsa oglase za gradsku vožnju i prvi auto.', 'filters' => ['brand' => 'Opel', 'model' => 'Corsa', 'sort' => 'best']],
            ['needles' => ['renault megane', 'megane'], 'label' => 'Pogledaj Renault Megane oglase', 'description' => 'Uporedi Megane ponude sa drugim kompaktnim polovnjacima.', 'filters' => ['brand' => 'Renault', 'model' => 'Megane', 'sort' => 'best']],
            ['needles' => ['renault clio', 'clio'], 'label' => 'Pogledaj Renault Clio oglase', 'description' => 'Proveri Clio oglase i obrati pažnju na servisnu istoriju malih dizela.', 'filters' => ['brand' => 'Renault', 'model' => 'Clio', 'sort' => 'best']],
            ['needles' => ['renault austral', 'austral'], 'label' => 'Pogledaj Renault Austral oglase', 'description' => 'Uporedi novije SUV oglase kroz garanciju, hibridni pogon i opremu.', 'filters' => ['brand' => 'Renault', 'model' => 'Austral', 'sort' => 'best']],
            ['needles' => ['toyota corolla', 'corolla'], 'label' => 'Pogledaj Toyota Corolla oglase', 'description' => 'Uporedi Corolla oglase i proveri da li hibridna reputacija prati cenu.', 'filters' => ['brand' => 'Toyota', 'model' => 'Corolla', 'sort' => 'best']],
            ['needles' => ['toyota auris', 'auris'], 'label' => 'Pogledaj Toyota Auris oglase', 'description' => 'Proveri Auris Hybrid oglase i uporedi ih sa drugim gradskim hibridima.', 'filters' => ['brand' => 'Toyota', 'model' => 'Auris', 'sort' => 'best']],
            ['needles' => ['toyota yaris', 'yaris'], 'label' => 'Pogledaj Toyota Yaris oglase', 'description' => 'Filtriraj Yaris oglase i proveri da li gradski hibrid vredi premiju.', 'filters' => ['brand' => 'Toyota', 'model' => 'Yaris', 'sort' => 'best']],
            ['needles' => ['toyota rav4', 'rav4'], 'label' => 'Pogledaj Toyota RAV4 oglase', 'description' => 'Uporedi RAV4 ponude sa ostalim SUV modelima po ceni i stanju.', 'filters' => ['brand' => 'Toyota', 'model' => 'RAV4', 'sort' => 'best']],
            ['needles' => ['hyundai tucson', 'tucson'], 'label' => 'Pogledaj Hyundai Tucson oglase', 'description' => 'Proveri Tucson oglase i uporedi hibridne, benzinske i dizel opcije.', 'filters' => ['brand' => 'Hyundai', 'model' => 'Tucson', 'sort' => 'best']],
            ['needles' => ['hyundai i30', 'i30'], 'label' => 'Pogledaj Hyundai i30 oglase', 'description' => 'Uporedi i30 oglase sa Ceed i drugim kompaktima bez premium cene.', 'filters' => ['brand' => 'Hyundai', 'model' => 'i30', 'sort' => 'best']],
            ['needles' => ['hyundai ioniq', 'ioniq'], 'label' => 'Pogledaj Hyundai Ioniq oglase', 'description' => 'Proveri hibridne Ioniq oglase i uporedi ukupnu cenu vlasništva.', 'filters' => ['brand' => 'Hyundai', 'model' => 'Ioniq', 'sort' => 'best']],
            ['needles' => ['kia ceed', 'ceed'], 'label' => 'Pogledaj Kia Ceed oglase', 'description' => 'Uporedi Ceed oglase po opremi, garanciji i servisnoj istoriji.', 'filters' => ['brand' => 'Kia', 'model' => 'Ceed', 'sort' => 'best']],
            ['needles' => ['kia sportage', 'sportage'], 'label' => 'Pogledaj Kia Sportage oglase', 'description' => 'Proveri Sportage oglase kroz garancijsku priču, opremu i kilometražu.', 'filters' => ['brand' => 'Kia', 'model' => 'Sportage', 'sort' => 'best']],
            ['needles' => ['nissan qashqai', 'qashqai'], 'label' => 'Pogledaj Nissan Qashqai oglase', 'description' => 'Uporedi Qashqai oglase sa drugim crossover modelima za grad i porodicu.', 'filters' => ['brand' => 'Nissan', 'model' => 'Qashqai', 'sort' => 'best']],
            ['needles' => ['peugeot 3008'], 'label' => 'Pogledaj Peugeot 3008 oglase', 'description' => 'Proveri 3008 oglase i uporedi cenu sa stanjem, opremom i motorom.', 'filters' => ['brand' => 'Peugeot', 'model' => '3008', 'sort' => 'best']],
            ['needles' => ['peugeot 2008'], 'label' => 'Pogledaj Peugeot 2008 oglase', 'description' => 'Filtriraj male crossover oglase i uporedi ih sa Captur ponudom.', 'filters' => ['brand' => 'Peugeot', 'model' => '2008', 'sort' => 'best']],
            ['needles' => ['mazda cx 5', 'mazda cx-5', 'cx 5', 'cx-5'], 'label' => 'Pogledaj Mazda CX-5 oglase', 'description' => 'Uporedi CX-5 oglase kroz stanje karoserije, motor i troškove održavanja.', 'filters' => ['brand' => 'Mazda', 'model' => 'CX-5', 'sort' => 'best']],
            ['needles' => ['ford kuga', 'kuga'], 'label' => 'Pogledaj Ford Kuga oglase', 'description' => 'Proveri Kuga oglase i uporedi SUV ponude po opremi i realnoj ceni.', 'filters' => ['brand' => 'Ford', 'model' => 'Kuga', 'sort' => 'best']],
        ];
    }

    /**
     * @return array<string, array{label: string, description: string, filters: array<string, mixed>}>
     */
    private function fuelRules(): array
    {
        return [
            'dizel' => ['label' => 'Pogledaj dizel oglase', 'description' => 'Filtriraj dizelaše i proveri da li kilometraža i režim vožnje opravdavaju rizik.', 'filters' => ['fuel_type' => FuelType::Diesel->value, 'sort' => 'best']],
            'benzin' => ['label' => 'Pogledaj benzinske oglase', 'description' => 'Uporedi benzince za gradsku vožnju i manje godišnje kilometraže.', 'filters' => ['fuel_type' => FuelType::Petrol->value, 'sort' => 'best']],
            'hibrid' => ['label' => 'Pogledaj hibridne oglase', 'description' => 'Proveri hibridne oglase i uporedi cenu sa potrošnjom, baterijom i stanjem.', 'filters' => ['fuel_type' => FuelType::Hybrid->value, 'sort' => 'best']],
            'elektric' => ['label' => 'Pogledaj električne oglase', 'description' => 'Uporedi električne polovnjake po dometu, bateriji, punjenju i realnoj ceni.', 'filters' => ['fuel_type' => FuelType::Electric->value, 'sort' => 'best']],
            'plin' => ['label' => 'Pogledaj oglase sa TNG pogonom', 'description' => 'Proveri da li ušteda na plinu prati stanje instalacije i realne troškove.', 'filters' => ['fuel_type' => FuelType::Lpg->value, 'sort' => 'best']],
        ];
    }

    /**
     * @param  array<int, string>  $sourceTerms
     * @param  array<int, string>  $sourceTags
     */
    private function scorePost(BlogPost $source, BlogPost $candidate, array $sourceTerms, array $sourceTags): int
    {
        $score = $source->category === $candidate->category ? 80 : 0;

        $candidateTags = $this->normalizedTags($candidate);
        $candidateTerms = $this->termsFor($candidate);

        $score += count(array_intersect($sourceTags, $candidateTags)) * 35;
        $score += min(12, count(array_intersect($sourceTerms, $candidateTerms))) * 4;

        return $score;
    }

    /**
     * @return array<int, string>
     */
    private function normalizedTags(BlogPost $post): array
    {
        return collect($post->tags ?? [])
            ->map(fn (mixed $tag) => $this->normalize((string) $tag))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private function termsFor(BlogPost $post): array
    {
        $text = implode(' ', array_filter([
            $post->title,
            $post->slug,
            $post->category,
            $post->excerpt,
            implode(' ', $post->tags ?? []),
        ]));

        preg_match_all('/[a-z0-9]{3,}/', $this->normalize($text), $matches);

        return collect($matches[0] ?? [])
            ->reject(fn (string $term) => in_array($term, $this->stopWords(), true))
            ->unique()
            ->values()
            ->all();
    }

    private function normalizedSearchText(BlogPost $post): string
    {
        return $this->normalize(implode(' ', array_filter([
            $post->title,
            $post->slug,
            $post->category,
            $post->excerpt,
            implode(' ', $post->tags ?? []),
        ])));
    }

    private function normalize(string $value): string
    {
        return trim((string) Str::of($value)
            ->lower()
            ->ascii()
            ->replace(['-', '_', '/', ',', ':'], ' ')
            ->squish());
    }

    /**
     * @return array<int, string>
     */
    private function stopWords(): array
    {
        return [
            'auto',
            'auta',
            'automobil',
            'automobila',
            'polovni',
            'polovnog',
            'polovnjak',
            'polovnjaka',
            'kupiti',
            'kupovina',
            'kupovinu',
            'kako',
            'koji',
            'koja',
            'sta',
            'bez',
            'ima',
            'vise',
            'smisla',
            'srbija',
            'srbiji',
        ];
    }

    /**
     * @param  array<int, string>  $needles
     */
    private function containsAny(string $text, array $needles): bool
    {
        return collect($needles)
            ->contains(fn (string $needle) => Str::contains($text, $this->normalize($needle)));
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array{label: string, description: string, url: string}
     */
    private function listingLink(string $label, string $description, array $filters): array
    {
        return [
            'label' => $label,
            'description' => $description,
            'url' => route('listings.index', array_filter($filters, fn (mixed $value) => $value !== null && $value !== '')),
        ];
    }
}
