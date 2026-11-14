<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Architecture\Support\ArchitectureRatchet;

/**
 * PublicDigit Hexagonal DDD baseline, enforcement A-1 to A-6 (docs/publicdigit/architecture/
 * publicdigit-hexagonal-ddd-baseline.md §7). A RATCHET: every known violation is listed INDIVIDUALLY in
 * tests/Architecture/baselines/architecture-ratchet.json as rule | file | exact dependency (with a count and metadata).
 * - a violation not listed (in a new file, or hidden in an already-listed file) fails;
 * - a listed violation that no longer exists fails too (remove it: the baseline only shrinks);
 * - nothing else is exempted.
 * Rules:
 *  A-1 Domain is free of Laravel, Eloquent, App\Models and facades;
 *  A-2 Application uses no facade, Eloquent or App\Models;
 *  A-3 a context imports nothing of another context except the PUBLISHED LANGUAGE (baselines/published-language.json,
 *      each type named explicitly; Principal ruling 2026-11-13) and Shared;
 *  A-4 delivery (app/Http, app/Console, app/Jobs, app/Listeners, routes, a context's Http/Console) never imports a
 *      context's Infrastructure or a WRITE-side port (…Repository, …Store, …Ledger), never names a bounded context's own
 *      table and never writes raw SQL (DB::insert/update/delete/statement/unprepared, DB::table(...)->insert/update/
 *      delete/…). Read-only query ports and Eloquent reads stay allowed: CLAUDE.md Rule 5;
 *  A-5 no code names the shared, re-pointed 'tenant' connection (D-222);
 *  A-6 Shared is a technical library (Principal ruling 2026-11-13): its contents are a CLOSED list
 *      (baselines/shared-contents.json); a new class there is a conscious, reviewed addition, never a business concept.
 * KNOWN BLIND SPOTS (asserted below, so they stay documented): class names inside strings; Eloquent writes ($m->save()).
 */
final class ArchitectureRatchetTest extends TestCase
{
    /**
     * The baseline may only shrink: growing it means changing this number too, visibly, in a reviewed diff.
     * History: 198 measured (f5b80720d) → 193 (MemberId published, Principal 2026-11-13) → 197 (+4 pre-existing chained
     * DB::table writes in delivery that the scanner could not see before; a measurement correction, not new code)
     * → 200 (+3 PBDIGIT-345 task 5c: the Platform-to-Identity ContactAccounts adapter, consumer-owned port, D-193 F-07)
     * → 201 (+1 PBDIGIT-345 task 6: the SignedInAccount adapter over Identity's CurrentAccount, E-1).
     */
    private const BASELINE_CEILING = 201;

    /**
     * The same for the total of occurrences: without it, raising an existing entry's count would let a new occurrence
     * in silently (found 2026-11-13, Principal verification request). History: 264 at introduction → 267 (PBDIGIT-345
     * task 5c, the same 3 entries, 1 occurrence each) → 268 (task 6, the same 1 entry).
     */
    private const OCCURRENCE_CEILING = 268;

    private const CLASSES = [
        'genuine', 'genuine (legacy)', 'genuine (minor)', 'genuine (placement)', 'misplaced ownership',
        'persistence or infrastructure leakage', 'placement', 'acceptable exception', 'published language (approved)',
        'legitimate (consumer-owned port)', 'unclear ownership', 'artefact (dangling)', 'artefact (unused import)',
    ];

    private static function fixtureRoot(): string
    {
        return dirname(__DIR__) . '/Fixtures/ArchitectureRatchet';
    }

    private static function fixture(): array
    {
        $root = self::fixtureRoot();

        return ArchitectureRatchet::compare(
            ArchitectureRatchet::scan($root, '.php.fixture', ArchitectureRatchet::loadList("$root/published-language.json")),
            ArchitectureRatchet::loadBaseline("$root/baseline.json"),
        );
    }

    private static function baselinePath(): string
    {
        return __DIR__ . '/baselines/architecture-ratchet.json';
    }

    public function test_a_listed_legacy_violation_is_tolerated(): void
    {
        $this->assertNotContains('A-1|app/Contexts/Alpha/Domain/Legacy.php.fixture|Illuminate\Support\Str', self::fixture()['new']);
    }

    public function test_a_new_violation_is_detected_for_every_rule(): void
    {
        $this->assertSame([
            'A-1|app/Contexts/Alpha/Domain/Fresh.php.fixture|Illuminate\Database\Eloquent\Model',
            'A-1|app/Contexts/Alpha/Domain/Legacy.php.fixture|DB::',
            'A-2|app/Contexts/Alpha/Application/UseCase.php.fixture|Illuminate\Support\Facades\Log',
            'A-2|app/Contexts/Alpha/Application/UseCase.php.fixture|Log::',
            'A-3|app/Contexts/Alpha/Application/Consumer.php.fixture|App\Contexts\Beta\Domain\BetaAggregate',
            'A-3|app/Contexts/Alpha/Application/UseCase.php.fixture|App\Contexts\Beta\Infrastructure\BetaRepository',
            'A-3|app/Contexts/Shared/Domain/MembershipFee.php.fixture|App\Contexts\Alpha\Domain\Legacy',
            'A-4|app/Http/Controllers/AliasController.php.fixture|App\Contexts\Beta\Infrastructure\BetaCache',
            'A-4|app/Http/Controllers/AliasController.php.fixture|App\Contexts\Beta\Infrastructure\BetaModel',
            'A-4|app/Http/Controllers/AliasController.php.fixture|DB::table->delete',
            'A-4|app/Http/Controllers/RogueController.php.fixture|App\Contexts\Beta\Application\Ports\BetaRepository',
            'A-4|app/Http/Controllers/RogueController.php.fixture|App\Contexts\Beta\Infrastructure\BetaRepository',
            'A-4|app/Http/Controllers/RogueController.php.fixture|DB::update',
            'A-4|app/Http/Controllers/RogueController.php.fixture|table:refgeo_publications',
            "A-5|app/Contexts/Beta/Infrastructure/BetaRepository.php.fixture|connection('tenant')",
        ], self::fixture()['new']);
    }

    public function test_a_violation_hidden_in_an_already_listed_file_is_detected(): void
    {
        $this->assertContains('A-1|app/Contexts/Alpha/Domain/Legacy.php.fixture|DB::', self::fixture()['new']);
    }

    public function test_a_fixed_violation_still_listed_is_reported_so_the_baseline_shrinks(): void
    {
        $this->assertSame(['A-1|app/Contexts/Alpha/Domain/Fixed.php.fixture|Illuminate\Support\Str'], self::fixture()['stale']);
    }

    public function test_a_second_occurrence_of_a_listed_dependency_in_the_same_file_is_detected(): void
    {
        $found = ['A-1|f.php|DB::' => 2];
        $baseline = ['A-1|f.php|DB::' => ['count' => 1, 'reason' => 'listed once']];

        $this->assertSame(['A-1|f.php|DB::'], ArchitectureRatchet::compare($found, $baseline)['new']);
    }

    public function test_only_the_named_published_identifier_crosses_the_boundary(): void
    {
        $new = self::fixture()['new'];

        $this->assertNotContains('A-3|app/Contexts/Alpha/Application/Consumer.php.fixture|App\Contexts\Beta\Domain\BetaId', $new);
        $this->assertContains('A-3|app/Contexts/Alpha/Application/Consumer.php.fixture|App\Contexts\Beta\Domain\BetaAggregate', $new);
    }

    public function test_known_blind_spots_stay_documented(): void
    {
        $all = implode("\n", array_keys(ArchitectureRatchet::scan(self::fixtureRoot(), '.php.fixture')));

        $this->assertStringNotContainsString('HiddenService', $all, 'a class named inside a string is NOT detected: if this now fails, detection improved; update the baseline §7 and this test');
        $this->assertStringNotContainsString('save', $all, 'Eloquent ->save() is NOT detected: if this now fails, detection improved; update the baseline §7 and this test');
    }

    public function test_an_unlisted_class_in_shared_is_detected_and_a_removed_one_is_stale(): void
    {
        $root = self::fixtureRoot();
        $result = ArchitectureRatchet::compareShared(ArchitectureRatchet::sharedContents($root, '.php.fixture'), ArchitectureRatchet::loadList("$root/shared-contents.json"));

        $this->assertSame(['new' => ['app/Contexts/Shared/Domain/MembershipFee.php.fixture'], 'stale' => []], $result);
        $this->assertSame(['new' => [], 'stale' => ['gone.php']], ArchitectureRatchet::compareShared([], ['gone.php']));
    }

    /** @return array<string, array{string}> */
    public static function rules(): array
    {
        return ['A-1 Domain purity' => ['A-1'], 'A-2 Application layer' => ['A-2'], 'A-3 cross-context' => ['A-3'], 'A-4 delivery boundary' => ['A-4'], 'A-5 organisation connection' => ['A-5']];
    }

    #[DataProvider('rules')]
    public function test_the_source_tree_has_no_violation_outside_the_baseline_and_no_stale_entry(string $rule): void
    {
        $root = dirname(__DIR__, 2);
        $result = ArchitectureRatchet::compare(
            ArchitectureRatchet::scan($root, '.php', ArchitectureRatchet::loadList(__DIR__ . '/baselines/published-language.json')),
            ArchitectureRatchet::loadBaseline(self::baselinePath()),
        );
        $mine = static fn (array $keys): array => array_values(array_filter($keys, static fn (string $k): bool => str_starts_with($k, $rule . '|')));

        $this->assertSame([], $mine($result['new']), "{$rule}: new violation(s). Fix them; do not add them to the baseline without a recorded reason.");
        $this->assertSame([], $mine($result['stale']), "{$rule}: fixed violation(s) still listed. Remove them from the baseline (it only shrinks).");
    }

    public function test_shared_contains_only_its_listed_technical_classes(): void
    {
        $root = dirname(__DIR__, 2);
        $result = ArchitectureRatchet::compareShared(ArchitectureRatchet::sharedContents($root, '.php'), ArchitectureRatchet::loadList(__DIR__ . '/baselines/shared-contents.json'));

        $this->assertSame([], $result['new'], 'A-6: a new class in Shared. Shared is a technical library: a business concept belongs to its owning context. A technical addition is listed consciously.');
        $this->assertSame([], $result['stale'], 'A-6: a listed Shared class no longer exists; remove it from the list.');
    }

    public function test_every_published_language_entry_names_an_existing_type(): void
    {
        foreach (ArchitectureRatchet::loadList(__DIR__ . '/baselines/published-language.json') as $type) {
            $this->assertTrue(class_exists($type) || interface_exists($type) || enum_exists($type), "{$type} does not exist");
        }
    }

    public function test_every_baseline_entry_carries_complete_metadata(): void
    {
        foreach (ArchitectureRatchet::loadBaseline(self::baselinePath()) as $key => $entry) {
            $this->assertIsInt($entry['count'] ?? null, "{$key}: count");
            $this->assertGreaterThan(0, $entry['count'], "{$key}: count");
            $this->assertContains($entry['class'] ?? null, self::CLASSES, "{$key}: classification outside the closed vocabulary");
            foreach (['reason', 'remediation', 'decision'] as $field) {
                $this->assertNotSame('', trim((string) ($entry[$field] ?? '')), "{$key} has no {$field}");
            }
        }
    }

    public function test_the_baseline_does_not_grow_beyond_its_recorded_ceiling(): void
    {
        $this->assertLessThanOrEqual(self::BASELINE_CEILING, count(ArchitectureRatchet::loadBaseline(self::baselinePath())), 'the baseline grew: an exception is an architecture change, not a way to turn a test green');
    }

    public function test_raising_an_entry_count_is_caught_by_the_occurrence_total(): void
    {
        $baseline = ['A-1|f.php|DB::' => ['count' => 1], 'A-2|g.php|Log::' => ['count' => 2]];
        $raised = ['A-1|f.php|DB::' => ['count' => 2], 'A-2|g.php|Log::' => ['count' => 2]];

        $this->assertSame(['entries' => 2, 'occurrences' => 3], ArchitectureRatchet::totals($baseline));
        $this->assertSame(['entries' => 2, 'occurrences' => 4], ArchitectureRatchet::totals($raised), 'same entries, more occurrences: visible');
    }

    public function test_the_baseline_occurrences_do_not_grow_beyond_their_recorded_ceiling(): void
    {
        $this->assertLessThanOrEqual(self::OCCURRENCE_CEILING, ArchitectureRatchet::totals(ArchitectureRatchet::loadBaseline(self::baselinePath()))['occurrences'],
            'an entry count was raised: a tolerated violation grew. That is an architecture change, not a way to turn a test green');
    }

    public function test_the_generated_inventory_matches_the_baseline(): void
    {
        $this->assertSame(
            ArchitectureRatchet::inventory(ArchitectureRatchet::loadBaseline(self::baselinePath())),
            (string) file_get_contents(dirname(__DIR__, 2) . '/docs/publicdigit/architecture/publicdigit-architecture-deviation-inventory.md'),
            'regenerate: php -r \'require "vendor/autoload.php"; echo Tests\Architecture\Support\ArchitectureRatchet::inventory(Tests\Architecture\Support\ArchitectureRatchet::loadBaseline("tests/Architecture/baselines/architecture-ratchet.json"));\' > docs/publicdigit/architecture/publicdigit-architecture-deviation-inventory.md',
        );
    }
}
