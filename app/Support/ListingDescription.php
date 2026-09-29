<?php

namespace App\Support;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

final class ListingDescription
{
    public const MAX_INPUT_BYTES = 50000;

    private static ?HtmlSanitizer $sanitizer = null;

    public static function sanitize(string $value): string
    {
        if (! self::hasMarkup($value)) {
            return $value;
        }

        $html = self::sanitizer()->sanitize($value);

        // Keep sanitized entities identifiable as HTML even when all wrappers were removed.
        return $html !== '' && ! self::hasMarkup($html) ? '<p>'.$html.'</p>' : $html;
    }

    public static function html(string $value): string
    {
        // Older listings and imports are plain text; keep their line breaks and literal entities.
        return self::hasMarkup($value)
            ? self::sanitizer()->sanitize($value)
            : '<p>'.nl2br(e($value), false).'</p>';
    }

    public static function text(string $value): string
    {
        if (self::hasMarkup($value)) {
            $html = self::sanitizer()->sanitize($value);
            $html = preg_replace('/<br\s*\/?>|<\/(?:p|li|ul|ol)>/i', ' ', $html);
            $value = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        $value = preg_replace('/[\x{200B}\x{FEFF}]/u', '', $value);

        return trim(preg_replace('/[\s\p{Z}]+/u', ' ', $value));
    }

    private static function hasMarkup(string $value): bool
    {
        return (bool) preg_match('/<\/?[a-z][^>]*>/i', $value);
    }

    private static function sanitizer(): HtmlSanitizer
    {
        if (self::$sanitizer === null) {
            $config = (new HtmlSanitizerConfig)->withMaxInputLength(self::MAX_INPUT_BYTES);

            foreach (['p', 'br', 'strong', 'b', 'em', 'i', 'u', 'ul', 'ol', 'li'] as $element) {
                $config = $config->allowElement($element, []);
            }

            // Retain pasted text from common wrappers without their styles or links.
            foreach (['div', 'span', 'a', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $element) {
                $config = $config->blockElement($element);
            }

            self::$sanitizer = new HtmlSanitizer($config);
        }

        return self::$sanitizer;
    }
}
