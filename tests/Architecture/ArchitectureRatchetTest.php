<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Architecture\Support\ArchitectureRatchet;

/**
 * PublicDigit Hexagonal DDD baseline, enforcement A-1 to A-5 (docs/publicdigit/architecture/
 * publicdigit-hexagonal-ddd-baseline.md §8). A RATCHET: every known violation is listed INDIVIDUALLY in
 * tests/Architecture/baselines/architecture-ratchet.json as rule | file | exact dependency (with a count and a reason).
 * - a violation not listed (in a new file, or hidden in an already-listed file) fails;
 * - a listed violation that no longer exists fails too (remove it: the baseline only shrinks);
 * - nothing else is exempted.
 * Rules:
 *  A-1 Domain is free of Laravel, Eloquent, App\Models and facades;
 *  A-2 Application uses no facade, Eloquent or App\Models;
 *  A-3 a context never imports another context's Infrastructure, and imports its Domain/Application only from the
 *      published-language list (Shared is provisionally exempt: baseline §4, X-3, pending the Principal);
 *  A-4 delivery (app/Http, app/Console, app/Jobs, app/Listeners, routes, a context's Http/Console) never imports a
 *      context's Infrastructure or a WRITE-side port (…Repository, …Store, …Ledger), never names a bounded context's own
 *      table and never writes raw SQL (DB::insert/update/delete/statement/unprepared). Read-only query ports and
 *      Eloquent reads stay allowed: CLAUDE.md Rule 5;
 *  A-5 no code names the shared, re-pointed 'tenant' connection (D-222).
 */
final class ArchitectureRatchetTest extends TestCase
{
    private static function fixture(): array
    {
        $root = dirname(__DIR__) . '/Fixtures/ArchitectureRatchet';

        return ArchitectureRatchet::compare(
            ArchitectureRatchet::scan($root, '.php.fixture'),
            ArchitectureRatchet::loadBaseline("$root/baseline.json"),
        );
    }

    public function test_a_listed_legacy_violation_is_tolerated(): void
    {
        $result = self::fixture();

        $this->assertNotContains('A-1|app/Contexts/Alpha/Domain/Legacy.php.fixture|Illuminate\Support\Str', $result['new']);
    }

    public function test_a_new_violation_is_detected_for_every_rule(): void
    {
        $this->assertSame([
            'A-1|app/Contexts/Alpha/Domain/Fresh.php.fixture|Illuminate\Database\Eloquent\Model',
            'A-1|app/Contexts/Alpha/Domain/Legacy.php.fixture|DB::',
            'A-2|app/Contexts/Alpha/Application/UseCase.php.fixture|Illuminate\Support\Facades\Log',
            'A-2|app/Contexts/Alpha/Application/UseCase.php.fixture|Log::',
            'A-3|app/Contexts/Alpha/Application/UseCase.php.fixture|App\Contexts\Beta\Infrastructure\BetaRepository',
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
            ArchitectureRatchet::scan($root, '.php'),
            ArchitectureRatchet::loadBaseline(__DIR__ . '/baselines/architecture-ratchet.json'),
        );
        $mine = static fn (array $keys): array => array_values(array_filter($keys, static fn (string $k): bool => str_starts_with($k, $rule . '|')));

        $this->assertSame([], $mine($result['new']), "{$rule}: new violation(s). Fix them; do not add them to the baseline without a recorded reason.");
        $this->assertSame([], $mine($result['stale']), "{$rule}: fixed violation(s) still listed. Remove them from the baseline (it only shrinks).");
    }

    public function test_every_baseline_entry_has_a_reason(): void
    {
        foreach (ArchitectureRatchet::loadBaseline(__DIR__ . '/baselines/architecture-ratchet.json') as $key => $entry) {
            $this->assertNotSame('', trim((string) ($entry['reason'] ?? '')), "{$key} has no reason");
        }
    }
}
