<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OptimizeBlogCoverImagesCommandTest extends TestCase
{
    public function test_command_optimizes_existing_generated_blog_covers(): void
    {
        Storage::fake('public');
        $path = 'blog/generated/test-cover.webp';
        $originalImage = $this->webpFixture(width: 640, height: 426, quality: 100);

        Storage::disk('public')->put($path, $originalImage);

        $this->artisan('blog:optimize-covers', [
            '--max-width' => 320,
            '--max-height' => 214,
            '--quality' => 60,
        ])->assertSuccessful();

        $optimizedImage = Storage::disk('public')->get($path);
        $dimensions = getimagesizefromstring($optimizedImage);

        $this->assertLessThan(strlen($originalImage), strlen($optimizedImage));
        $this->assertSame(320, $dimensions[0]);
        $this->assertSame(213, $dimensions[1]);
    }

    public function test_dry_run_reports_optimization_without_writing_files(): void
    {
        Storage::fake('public');
        $path = 'blog/generated/test-cover.webp';
        $originalImage = $this->webpFixture(width: 640, height: 426, quality: 100);

        Storage::disk('public')->put($path, $originalImage);

        $this->artisan('blog:optimize-covers', [
            '--dry-run' => true,
            '--max-width' => 320,
            '--max-height' => 214,
            '--quality' => 60,
        ])->assertSuccessful();

        $this->assertSame($originalImage, Storage::disk('public')->get($path));
    }

    public function test_command_skips_covers_that_are_already_optimized(): void
    {
        Storage::fake('public');
        $path = 'blog/generated/test-cover.webp';
        $optimizedImage = $this->webpFixture(width: 320, height: 213, quality: 60);

        Storage::disk('public')->put($path, $optimizedImage);

        $this->artisan('blog:optimize-covers', [
            '--max-width' => 320,
            '--max-height' => 214,
            '--max-kb' => 350,
            '--quality' => 60,
        ])->assertSuccessful();

        $this->assertSame($optimizedImage, Storage::disk('public')->get($path));
    }

    private function webpFixture(int $width, int $height, int $quality): string
    {
        $image = imagecreatetruecolor($width, $height);

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $red = ($x * 7 + $y * 3) % 256;
                $green = ($x * 5 + $y * 11) % 256;
                $blue = ($x * 13 + $y * 17) % 256;
                $color = imagecolorallocate($image, $red, $green, $blue);

                imagesetpixel($image, $x, $y, $color);
            }
        }

        ob_start();
        imagewebp($image, null, $quality);
        $contents = (string) ob_get_clean();
        imagedestroy($image);

        return $contents;
    }
}
