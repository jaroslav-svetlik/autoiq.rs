<?php

namespace App\Services;

class OptimizedImage
{
    public function __construct(
        public readonly string $contents,
        public readonly int $originalBytes,
        public readonly int $optimizedBytes,
        public readonly int $originalWidth,
        public readonly int $originalHeight,
        public readonly int $width,
        public readonly int $height,
        public readonly string $format,
    ) {}

    public function savedBytes(): int
    {
        return max(0, $this->originalBytes - $this->optimizedBytes);
    }

    public function savedPercentage(): float
    {
        if ($this->originalBytes <= 0) {
            return 0.0;
        }

        return ($this->savedBytes() / $this->originalBytes) * 100;
    }
}
