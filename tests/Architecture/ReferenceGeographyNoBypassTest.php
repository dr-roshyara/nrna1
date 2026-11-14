<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-4f, the no-bypass guard (G-4 interpretation B-8, B-9; v3.11 §15 single write path): there is ONE
 * authorised path for every Geography write, and nothing can reach around it.
 *
 * Rules, checked over the real source tree (app, routes, config, database/seeders):
 *  1. the publication ledger (ports and adapter) is used only by PublishRevision, the ledger and the provider;
 *  2. the revision repository only by RevisionService and the Geography adapters;
 *  3. the roster store only by the roster services, RevisionService (read) and the Geography adapters;
 *  4. no refgeo_ table is named outside Geography's Infrastructure (and its migrations);
 *  5. the Revision aggregate is imported only by the Domain, the use cases and the Geography adapters;
 *  6. delivery code (HTTP, console, jobs, listeners, routes) never reaches Geography's Infrastructure or Ports, only
 *     its application use cases.
 * The same scanner must find a violation of EVERY rule in tests/Fixtures/ReferenceGeographyBypass (deliberate
 * bypasses), so a future bypass fails this test. Adding a legitimate user of a port is a deliberate allow-list change.
 */
final class ReferenceGeographyNoBypassTest extends TestCase
{
    private const CTX = 'app/Contexts/ReferenceGeography/';

    /** @var array<string, array{string, list<string>}> rule => [pattern, allowed files (prefix match)] */
    private const RULES = [
        '1 ledger' => ['/\b(PublicationLedger|LockedLedger|PostgresPublicationLedger)\b/', [
            self::CTX . 'Application/PublishRevision.php', self::CTX . 'Application/Ports/PublicationLedger.php', self::CTX . 'Application/Ports/LockedLedger.php',
            // G-6c-3 (design revision 3 §5, T-1, accepted 2026-11-14): an Identity merge or reversal and its D-90 notices are one act under the lock
            self::CTX . 'Application/IdentityNoticeConsumer.php',
            // G-6c-4 (design revision 3 §7, accepted 2026-11-14): a confirmation is appended under the lock
            self::CTX . 'Application/ConfirmPublication.php',
            self::CTX . 'Infrastructure/PostgresPublicationLedger.php', self::CTX . 'Infrastructure/Providers/ReferenceGeographyServiceProvider.php',
        ]],
        '2 revision repository' => ['/\b(RevisionRepository|PostgresRevisionRepository)\b/', [
            self::CTX . 'Application/RevisionService.php', self::CTX . 'Application/Ports/RevisionRepository.php',
            self::CTX . 'Infrastructure/PostgresRevisionRepository.php', self::CTX . 'Infrastructure/PostgresPublicationLedger.php',
            self::CTX . 'Infrastructure/PostgresPublishedAtlas.php', self::CTX . 'Infrastructure/Providers/ReferenceGeographyServiceProvider.php',
        ]],
        '3 roster store' => ['/\b(StewardRosterStore|PostgresStewardRosterStore)\b/', [
            self::CTX . 'Application/StewardRosterService.php', self::CTX . 'Application/RevisionService.php',
            self::CTX . 'Application/Ports/StewardRosterStore.php', self::CTX . 'Infrastructure/PostgresStewardRosterStore.php',
            self::CTX . 'Infrastructure/PostgresPublicationLedger.php', self::CTX . 'Infrastructure/Providers/ReferenceGeographyServiceProvider.php',
        ]],
        '4 tables' => ['/refgeo_/', [self::CTX . 'Infrastructure/', 'database/migrations/']],
        '5 aggregate' => ['/ReferenceGeography\\\\Domain\\\\Revision\b|\bRevision::(prepare|fromSnapshot)\(/', [
            self::CTX . 'Domain/', self::CTX . 'Application/', self::CTX . 'Infrastructure/PostgresRevisionRepository.php', self::CTX . 'Infrastructure/PostgresPublicationLedger.php',
        ]],
    ];

    private const DELIVERY = ['app/Http/', 'app/Console/', 'app/Jobs/', 'app/Listeners/', 'routes/'];

    /** @return list<string> "rule: file" for every violation under $root */
    private static function findings(string $root, string $extension): array
    {
        $out = [];
        foreach (['app', 'routes', 'config', 'database'] as $top) {
            if (!is_dir("$root/$top")) {
                continue;
            }
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator("$root/$top", \FilesystemIterator::SKIP_DOTS));
            foreach ($files as $file) {
                if (!str_ends_with($file->getPathname(), $extension)) {
                    continue;
                }
                $relative = substr($file->getPathname(), strlen($root) + 1);
                $source = (string) file_get_contents($file->getPathname());
                foreach (self::RULES as $rule => [$pattern, $allowed]) {
                    if (preg_match($pattern, $source) === 1 && !self::allowed($relative, $allowed)) {
                        $out[] = "{$rule}: {$relative}";
                    }
                }
                if (self::allowed($relative, self::DELIVERY) && preg_match('/ReferenceGeography\\\\(Infrastructure|Application\\\\Ports)\\\\/', $source) === 1) {
                    $out[] = "6 delivery: {$relative}";
                }
            }
        }
        sort($out);

        return $out;
    }

    /** @param list<string> $allowed */
    private static function allowed(string $relative, array $allowed): bool
    {
        foreach ($allowed as $prefix) {
            if (str_starts_with($relative, $prefix)) {
                return true;
            }
        }

        return false;
    }

    public function test_the_source_tree_has_no_path_around_the_authorised_use_cases(): void
    {
        $this->assertSame([], self::findings(dirname(__DIR__, 2), '.php'));
    }

    public function test_the_guard_finds_a_deliberate_bypass_of_every_rule(): void
    {
        $found = self::findings(dirname(__DIR__) . '/Fixtures/ReferenceGeographyBypass', '.php.fixture');

        $this->assertSame([
            '1 ledger: app/Http/Controllers/RoguePublishController.php.fixture',
            '1 ledger: routes/rogue.php.fixture',
            '2 revision repository: app/Console/Commands/RogueRepreparesCommand.php.fixture',
            '3 roster store: app/Jobs/RogueAppointJob.php.fixture',
            '4 tables: app/Services/RogueSqlService.php.fixture',
            '5 aggregate: app/Services/RogueSqlService.php.fixture',
            '6 delivery: app/Console/Commands/RogueRepreparesCommand.php.fixture',
            '6 delivery: app/Http/Controllers/RoguePublishController.php.fixture',
            '6 delivery: app/Jobs/RogueAppointJob.php.fixture',
            '6 delivery: routes/rogue.php.fixture',
        ], $found);
        foreach (['1 ledger', '2 revision repository', '3 roster store', '4 tables', '5 aggregate', '6 delivery'] as $rule) {
            $this->assertNotEmpty(array_filter($found, static fn (string $f): bool => str_starts_with($f, $rule . ':')), "rule {$rule} must detect its bypass");
        }
    }
}
