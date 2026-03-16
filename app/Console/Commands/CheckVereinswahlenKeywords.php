<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SeoService;

class CheckVereinswahlenKeywords extends Command
{
    protected $signature = 'seo:check-vereinswahlen
                            {--locale=de : Locale to check (de, en, np)}';

    protected $description = 'Check keyword density for the Vereinswahlen landing page';

    private const KEYWORDS = [
        'de' => [
            'Digitale Online Wahlen für Verein',
            'Vereinswahlen online',
            'digitale Vorstandswahl Verein',
            'DSGVO',
            'Ende-zu-Ende',
        ],
        'en' => [
            'digital online elections for associations',
            'association voting',
            'board election',
            'GDPR',
            'end-to-end',
        ],
        'np' => [
            'डिजिटल अनलाइन चुनाव',
            'GDPR',
        ],
    ];

    public function handle(SeoService $seo): int
    {
        $locale = $this->option('locale');
        app()->setLocale($locale);

        $meta = $seo->getMeta('vereinswahlen', [], true, ['vereinswahlen' => true]);
        $keywords = self::KEYWORDS[$locale] ?? self::KEYWORDS['de'];

        $this->info("=== Keyword check — locale: {$locale} ===");
        $this->newLine();
        $this->line("Title:       {$meta['title']}");
        $this->line("Description: {$meta['description']}");
        $this->newLine();

        $headers = ['Keyword', 'In Title', 'In Description'];
        $rows    = [];

        foreach ($keywords as $keyword) {
            $inTitle = mb_stripos($meta['title'], $keyword) !== false;
            $inDesc  = mb_stripos($meta['description'], $keyword) !== false;
            $rows[]  = [$keyword, $inTitle ? '✅' : '❌', $inDesc ? '✅' : '❌'];
        }

        $this->table($headers, $rows);

        return self::SUCCESS;
    }
}
