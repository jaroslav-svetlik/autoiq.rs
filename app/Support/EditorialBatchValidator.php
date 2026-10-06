<?php

namespace App\Support;

use App\Models\BlogPost;
use InvalidArgumentException;

/** Structural publication gate; never a substitute for human editorial review. */
final class EditorialBatchValidator
{
    public static function validate(array $posts): void
    {
        $expected = ['Analiza tržišta' => 1, 'Kupovina polovnjaka' => 2, 'Poređenje modela' => 1, 'Provera vozila' => 1];
        $actual = array_count_values(array_column($posts, 'category'));
        ksort($actual);
        if (count($posts) !== 5 || $actual !== $expected) {
            throw new InvalidArgumentException('Editorial batch must contain exactly five posts in the approved category mix.');
        }
        foreach (['slug', 'title'] as $field) {
            if (count(array_unique(array_column($posts, $field))) !== 5) {
                throw new InvalidArgumentException('Editorial batch has duplicate '.$field.' values.');
            }
        }
        foreach ($posts as $post) {
            foreach (['title', 'slug', 'excerpt', 'content', 'highlights', 'tags', 'meta_title', 'meta_description', 'palette'] as $field) {
                if (empty($post[$field])) {
                    throw new InvalidArgumentException('Missing reviewed editorial field: '.$field);
                }
            }
            $body = preg_replace('/^## .+$/m', '', $post['content']);
            $words = count(preg_split('/\s+/u', trim($body), -1, PREG_SPLIT_NO_EMPTY));
            [$min, $max] = in_array($post['category'], ['Provera vozila', 'Analiza tržišta'], true) ? [500, 800] : [700, 1100];
            if ($words < $min || $words > $max) {
                throw new InvalidArgumentException($post['slug'].' has '.$words.' body words; expected '.$min.'–'.$max.'.');
            }
            $blocks = (new BlogPost(['content' => $post['content']]))->contentBlocks();
            $headings = $blocks->where('type', 'heading');
            if ($headings->count() < 4 || $headings->count() > 6 || $blocks->where('type', 'paragraph')->count() < 8) {
                throw new InvalidArgumentException('Insufficient separately rendered sections/paragraphs: '.$post['slug']);
            }
            foreach ($headings as $heading) {
                if (str_contains($heading['text'], "\n")) {
                    throw new InvalidArgumentException('Heading swallowed a body paragraph: '.$post['slug']);
                }
            }
            foreach (['Nastavi kada', 'Pregovaraj za jednu', 'Odustani kada', 'Stručni vodič: stanje primerka'] as $template) {
                if (str_contains($post['content'], $template)) {
                    throw new InvalidArgumentException('Legacy template in reviewed copy: '.$post['slug']);
                }
            }
        }
    }
}
