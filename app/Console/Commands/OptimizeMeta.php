<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SeoService;

class OptimizeMeta extends Command
{
    protected $signature = 'seo:optimize-meta
                            {--locale=de : Locale to analyze (de, en, np)}
                            {--all-locales : Analyze all supported locales}';

    protected $description = 'Analyze meta tag lengths and suggest optimizations';

    private const PAGES = [
        'home', 'about', 'faq', 'security', 'pricing',
        'login', 'register', 'dashboard', 'profile', 'vereinswahlen',
        'elections.index', 'elections.show', 'election.result',
    ];

    public function handle(SeoService $seo): int
    {
        $locales = $this->option('all-locales')
            ? config('meta.supported_locales', ['de', 'en', 'np'])
            : [$this->option('locale')];

        foreach ($locales as $locale) {
            app()->setLocale($locale);
            $this->info("=== Locale: {$locale} ===");
            $this->newLine();

            $headers = ['Page', 'Title (chars)', 'Desc (chars)', 'Status'];
            $rows = [];
            $issues = 0;

            foreach (self::PAGES as $page) {
                $meta = $seo->getMeta($page, [], true);

                $titleLen = mb_strlen($meta['title']);
                $descLen  = mb_strlen($meta['description']);

                $titleOk = $titleLen <= 60;
                $descOk  = $descLen <= 160;
                $status  = ($titleOk && $descOk) ? '✅ OK' : '⚠️  Fix';

                if (!$titleOk || !$descOk) {
                    $issues++;
                }

                $rows[] = [
                    $page,
                    $titleLen . ($titleOk ? '' : ' ⚠️'),
                    $descLen  . ($descOk  ? '' : ' ⚠️'),
                    $status,
                ];
            }

            $this->table($headers, $rows);

            if ($issues === 0) {
                $this->info("All meta tags within optimal lengths.");
            } else {
                $this->warn("{$issues} page(s) have meta tags exceeding optimal lengths.");
                $this->line("  Title: max 60 chars | Description: max 160 chars");
            }

            $this->newLine();
        }

        return self::SUCCESS;
    }
}
