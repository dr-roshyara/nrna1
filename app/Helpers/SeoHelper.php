<?php

namespace App\Helpers;

class SeoHelper
{
    /**
     * Truncate a title to the optimal SEO length (50–60 chars) at a word boundary.
     */
    public static function optimizeTitle(string $title, int $maxLength = 60): string
    {
        if (mb_strlen($title) <= $maxLength) {
            return $title;
        }

        $truncated = mb_substr($title, 0, $maxLength - 3);
        $lastSpace = mb_strrpos($truncated, ' ');

        if ($lastSpace !== false) {
            $truncated = mb_substr($truncated, 0, $lastSpace);
        }

        return $truncated . '...';
    }

    /**
     * Truncate a description to the optimal SEO length (150–160 chars) at a word boundary.
     */
    public static function optimizeDescription(string $description, int $maxLength = 160): string
    {
        if (mb_strlen($description) <= $maxLength) {
            return $description;
        }

        $truncated = mb_substr($description, 0, $maxLength - 3);
        $lastSpace = mb_strrpos($truncated, ' ');

        if ($lastSpace !== false) {
            $truncated = mb_substr($truncated, 0, $lastSpace);
        }

        return $truncated . '...';
    }
}
