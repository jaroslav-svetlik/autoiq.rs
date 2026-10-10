<?php

// Public, read-only verification: a 200 response alone never passes this gate.
use App\Models\BlogPost;
use App\Support\ReviewedEditorialBatch;
use Illuminate\Support\Facades\Http;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $date = $argv[1] ?? now('Europe/Belgrade')->toDateString();
    $origin = rtrim($argv[2] ?? 'https://autoiq.rs', '/');
    if (! preg_match('#^https://[a-z0-9.-]+$#D', $origin)) {
        throw new RuntimeException('Expected a public HTTPS origin without a path.');
    }
    $posts = ReviewedEditorialBatch::load($date);
    $normalize = static fn (string $text): string => preg_replace('/\s+/u', ' ', trim($text));
    $get = static function (string $url) {
        $response = Http::timeout(30)->retry(3, 1000)->get($url);
        if ($response->status() !== 200) {
            throw new RuntimeException('Public URL failed: '.$url);
        }
        return $response;
    };
    $sitemap = $get($origin.'/sitemap.xml');
    $xml = simplexml_load_string($sitemap->body(), SimpleXMLElement::class, LIBXML_NONET);
    if (! $xml) {
        throw new RuntimeException('Sitemap is not valid XML.');
    }
    $locations = $xml->xpath('//*[local-name()="loc"]');
    $locations = array_map(static fn ($node): string => (string) $node, $locations);
    $verified = [];
    foreach ($posts as $source) {
        $url = $origin.'/blog/'.$source['slug'];
        $response = $get($url);
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8">'.$response->body(), LIBXML_NONET);
        libxml_clear_errors();
        $xpath = new DOMXPath($dom);
        $h1 = $xpath->query('//h1')->item(0);
        if (! $h1 || $normalize($h1->textContent) !== $normalize($source['title'])) {
            throw new RuntimeException('Missing public article title: '.$url);
        }
        $canonical = $xpath->query('//link[@rel="canonical"]/@href')->item(0);
        if (! $canonical || $canonical->nodeValue !== $url) {
            throw new RuntimeException('Missing/wrong canonical: '.$url);
        }
        $texts = [];
        foreach ($xpath->query('//article//p | //article//h2 | //article//h3 | //article//summary') as $node) {
            $texts[] = $normalize($node->textContent);
        }
        $post = new BlogPost($source);
        foreach ($post->contentBlocks() as $block) {
            foreach ($block['type'] === 'faq' ? [$block['question'], $block['answer']] : [$block['text']] as $text) {
                if (! in_array($normalize($text), $texts, true)) {
                    throw new RuntimeException('Missing visible content block: '.$url.' / '.mb_substr($text, 0, 80));
                }
            }
        }
        $visible = $normalize($xpath->query('//body')->item(0)->textContent);
        foreach (array_merge([$source['excerpt']], $source['highlights']) as $text) {
            if (! str_contains($visible, $normalize($text))) {
                throw new RuntimeException('Missing visible excerpt/highlight: '.$url);
            }
        }
        $coverPath = '/storage/blog/generated/'.$source['slug'].'.webp';
        $image = $xpath->query('//img[starts-with(@src, "'.$coverPath.'")]/@src')->item(0);
        if (! $image) {
            throw new RuntimeException('Missing article WebP binding: '.$url);
        }
        $imageUrl = $origin.$image->nodeValue;
        $cover = $get($imageUrl);
        $size = @getimagesizefromstring($cover->body());
        $bitmap = @imagecreatefromstring($cover->body());
        if (! $size || $size[0] !== 1280 || $size[1] !== 720 || ($size['mime'] ?? '') !== 'image/webp'
            || ! str_starts_with((string) $cover->header('Content-Type'), 'image/webp') || ! $bitmap) {
            throw new RuntimeException('Public cover is not a decodable 1280x720 WebP: '.$imageUrl);
        }
        imagedestroy($bitmap);
        if (count(array_keys($locations, $url, true)) !== 1) {
            throw new RuntimeException('Article must appear exactly once in sitemap: '.$url);
        }
        $verified[] = ['url' => $url, 'blocks' => $post->contentBlocks()->count(), 'webp_url' => $imageUrl,
            'webp_sha256' => hash('sha256', $cover->body()), 'sitemap' => true];
    }
    echo json_encode(['status' => 'complete public batch', 'date' => $date, 'verified_at' => gmdate(DATE_ATOM),
        'articles' => $verified], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR).PHP_EOL;
} catch (Throwable $exception) {
    fwrite(STDERR, $exception->getMessage().PHP_EOL);
    exit(1);
}
