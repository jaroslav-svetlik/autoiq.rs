<?php

namespace App\Services;

use RuntimeException;

class BlogCoverImageOptimizer
{
    public function optimize(
        string $contents,
        ?string $format = null,
        ?int $maxWidth = null,
        ?int $maxHeight = null,
        ?int $quality = null,
    ): OptimizedImage {
        $source = @imagecreatefromstring($contents);

        if (! $source) {
            throw new RuntimeException('Slika ne može da se učita za optimizaciju.');
        }

        $format = $this->normalizeFormat($format ?: (string) config('services.openai.image_format', 'webp'));
        $maxWidth = $this->positiveInteger($maxWidth, (int) config('services.openai.image_max_width', 1280));
        $maxHeight = $this->positiveInteger($maxHeight, (int) config('services.openai.image_max_height', 854));
        $quality = $this->clampQuality($quality ?? (int) config('services.openai.image_optimization_quality', 76));

        $originalWidth = imagesx($source);
        $originalHeight = imagesy($source);
        [$width, $height] = $this->targetDimensions($originalWidth, $originalHeight, $maxWidth, $maxHeight);

        $canvas = $this->resampledCanvas($source, $originalWidth, $originalHeight, $width, $height);
        $optimized = $this->encode($canvas, $format, $quality);

        imagedestroy($source);
        imagedestroy($canvas);

        return new OptimizedImage(
            contents: $optimized,
            originalBytes: strlen($contents),
            optimizedBytes: strlen($optimized),
            originalWidth: $originalWidth,
            originalHeight: $originalHeight,
            width: $width,
            height: $height,
            format: $format,
        );
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function targetDimensions(int $width, int $height, int $maxWidth, int $maxHeight): array
    {
        if ($width <= $maxWidth && $height <= $maxHeight) {
            return [$width, $height];
        }

        $scale = min($maxWidth / $width, $maxHeight / $height);

        return [
            max(1, (int) round($width * $scale)),
            max(1, (int) round($height * $scale)),
        ];
    }

    private function resampledCanvas(\GdImage $source, int $originalWidth, int $originalHeight, int $width, int $height): \GdImage
    {
        $canvas = imagecreatetruecolor($width, $height);

        if (! $canvas) {
            throw new RuntimeException('Nije moguće pripremiti sliku za optimizaciju.');
        }

        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);

        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);

        if ($transparent !== false) {
            imagefilledrectangle($canvas, 0, 0, $width, $height, $transparent);
        }

        imagecopyresampled(
            $canvas,
            $source,
            0,
            0,
            0,
            0,
            $width,
            $height,
            $originalWidth,
            $originalHeight,
        );

        return $canvas;
    }

    private function encode(\GdImage $image, string $format, int $quality): string
    {
        ob_start();

        $success = match ($format) {
            'jpeg' => imagejpeg($image, null, $quality),
            'png' => imagepng($image, null, 6),
            default => imagewebp($image, null, $quality),
        };

        $contents = (string) ob_get_clean();

        if (! $success || $contents === '') {
            throw new RuntimeException('Optimizovana slika nije mogla da se snimi.');
        }

        return $contents;
    }

    private function normalizeFormat(string $format): string
    {
        $format = strtolower(trim($format));

        return match ($format) {
            'jpg' => 'jpeg',
            'jpeg', 'png', 'webp' => $format,
            default => 'webp',
        };
    }

    private function positiveInteger(?int $value, int $fallback): int
    {
        return $value !== null && $value > 0 ? $value : max(1, $fallback);
    }

    private function clampQuality(int $quality): int
    {
        return min(100, max(1, $quality));
    }
}
