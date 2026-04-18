<?php

namespace App\Services;

use App\Models\BlogPost;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class BlogCoverImageGenerator
{
    public function generate(
        BlogPost $post,
        ?string $model = null,
        ?string $size = null,
        ?string $quality = null,
        ?string $format = null,
    ): string {
        $key = config('services.openai.key');

        if (! is_string($key) || trim($key) === '') {
            throw new RuntimeException('OPENAI_API_KEY nije podešen.');
        }

        $format = $this->normalizeFormat($format ?: (string) config('services.openai.image_format', 'webp'));
        $payload = [
            'model' => $model ?: (string) config('services.openai.image_model', 'gpt-image-1.5'),
            'prompt' => $this->promptFor($post),
            'size' => $size ?: (string) config('services.openai.image_size', '1536x1024'),
            'quality' => $quality ?: (string) config('services.openai.image_quality', 'medium'),
            'output_format' => $format,
            'n' => 1,
        ];

        $response = Http::withToken($key)
            ->acceptJson()
            ->asJson()
            ->timeout((int) config('services.openai.timeout', 120))
            ->post('https://api.openai.com/v1/images/generations', $payload);

        if (! $response->successful()) {
            $message = data_get($response->json(), 'error.message') ?: $response->body();

            throw new RuntimeException('OpenAI generisanje slike nije uspelo: '.$message);
        }

        $payload = $response->json();

        if (! is_array($payload)) {
            throw new RuntimeException('OpenAI odgovor nije ispravan JSON.');
        }

        $image = $this->imageBytesFromResponse($payload);
        $path = 'blog/generated/'.$post->slug.'.'.$this->extensionFor($format);

        Storage::disk('public')->put($path, $image);

        return $path;
    }

    public function promptFor(BlogPost $post): string
    {
        $tags = collect($post->tags ?: [])
            ->filter()
            ->take(6)
            ->implode(', ');

        return trim(implode("\n", array_filter([
            'Create a modern editorial automotive blog cover for AutoIQ.rs, a Serbian used-car market website.',
            'The image should feel premium, realistic, current, and useful for readers comparing cars or checking market value.',
            'Use a wide landscape composition suitable for a 16:9 blog hero image.',
            'Show generic modern vehicles and Serbian road or urban context when relevant, but do not show exact brand logos, trademark badges, readable license plates, UI screenshots, text overlays, captions, watermarks, or people posing for camera.',
            'Visual tone: clean, confident, sharp lighting, restrained colors, contemporary automotive photography style.',
            'Article title: '.$post->title,
            'Category: '.$post->category,
            $post->excerpt ? 'Article summary: '.$post->excerpt : null,
            $tags ? 'Topic tags: '.$tags : null,
        ])));
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function imageBytesFromResponse(array $payload): string
    {
        $base64 = data_get($payload, 'data.0.b64_json');

        if (is_string($base64) && $base64 !== '') {
            $image = base64_decode($base64, true);

            if ($image !== false) {
                return $image;
            }
        }

        $url = data_get($payload, 'data.0.url');

        if (is_string($url) && Str::startsWith($url, ['http://', 'https://'])) {
            $response = Http::timeout((int) config('services.openai.timeout', 120))->get($url);

            if ($response->successful() && $response->body() !== '') {
                return $response->body();
            }
        }

        throw new RuntimeException('OpenAI odgovor ne sadrži ispravnu sliku.');
    }

    private function normalizeFormat(string $format): string
    {
        $format = strtolower(trim($format));

        return in_array($format, ['png', 'jpeg', 'webp'], true) ? $format : 'webp';
    }

    private function extensionFor(string $format): string
    {
        return $format === 'jpeg' ? 'jpg' : $format;
    }
}
