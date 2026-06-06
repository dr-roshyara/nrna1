<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\File;

class ArticleController extends Controller
{
    public function showDddArticlePartOne()
    {
        $filePath = base_path('docs/articles/The Question That Changed Everything_01.md');

        if (!File::exists($filePath)) {
            abort(404, 'Article not found');
        }

        $raw = File::get($filePath);
        $article = $this->parseArticle($raw);

        return Inertia::render('Articles/DddArticlePartOne', [
            'article' => $article,
            'breadcrumbs' => [],
        ]);
    }

    private function parseArticle($raw)
    {
        $lines = explode("\n", $raw);
        $title = '';
        $subtitle = '';
        $author = '';
        $authorBio = '';
        $dividerCount = 0;
        $content = [];

        foreach ($lines as $line) {
            $trimmedLine = trim($line);

            // Count dividers to know when header ends
            if ($trimmedLine === '---') {
                $dividerCount++;
                if ($dividerCount >= 2) {
                    continue;
                }
                continue;
            }

            // Parse header section (before second divider)
            if ($dividerCount < 2) {
                if (preg_match('/^#\s+(.+)$/i', $trimmedLine, $matches) && strpos($trimmedLine, '##') !== 0) {
                    $title = trim($matches[1]);
                } elseif (preg_match('/^##\s+(.+)$/i', $trimmedLine, $matches)) {
                    $subtitle = trim($matches[1]);
                } elseif (preg_match('/\*\*By\s+(.+?)\*\*/i', $trimmedLine, $matches)) {
                    $author = trim($matches[1]);
                }
            } else {
                // Collect content after header
                $content[] = $line;
            }
        }

        // Extract author bio from content (appears after final **Author Name** line)
        $bodyContent = implode("\n", $content);

        // Look for author bio pattern: **Dr. Nab Raj Roshyara** is a...
        if (preg_match('/\*\*' . preg_quote($author, '/') . '\*\*\s+is\s+(.+?)(?:\n\n|$)/s', $bodyContent, $matches)) {
            $authorBio = $author . ' is ' . trim($matches[1]);
            // Remove author bio from body content
            $bodyContent = preg_replace('/\*\*' . preg_quote($author, '/') . '\*\*\s+is\s+.+?(?:\n\n|$)/s', '', $bodyContent);
        }

        $bodyContent = trim($bodyContent);

        return [
            'title' => $title,
            'subtitle' => $subtitle,
            'author' => $author,
            'author_bio' => $authorBio,
            'published_date' => null,
            'part' => 1,
            'content' => $bodyContent,
            'next_part' => [
                'title' => 'Part Two: The Constitutional Universe',
                'status' => 'coming_soon',
            ],
        ];
    }
}
