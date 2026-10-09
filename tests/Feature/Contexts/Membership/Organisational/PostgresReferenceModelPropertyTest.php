<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;
use Tests\Unit\Contexts\Membership\Application\Organisational\Support\ReferencePropertyRun;

/**
 * Slice 1b contract T11: handlers + the PostgreSQL adapter + Slice 0 resolution against
 * the independent reference model (the same run as the in-memory adapter, with fewer
 * seeds for speed). Fresh organisations per seed. PBDIGIT-260.
 */
final class PostgresReferenceModelPropertyTest extends TestCase
{
    public function test_handlers_and_postgresql_agree_with_the_reference_model(): void
    {
        $organisation = static function (): string {
            $id = (string) Str::uuid();
            DB::table('organisations')->insert(['id' => $id, 'name' => 'm1b-prop', 'slug' => 'm1b-prop-' . $id, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);

            return $id;
        };
        $run = new ReferencePropertyRun();
        $clock = 1_760_000_000_000_000;

        $failures = $run->run(
            static function () use (&$clock) {
                return new PostgresMemberRepository(DB::connection(), static function () use (&$clock): int {
                    return $clock += 1_000;
                });
            },
            static fn (int $seed): array => [$organisation(), $organisation()],
            25,
            30,
        );

        $this->assertSame([], $failures);
        ksort($run->stats);
        fwrite(STDERR, 'outcomes: ' . json_encode($run->stats) . PHP_EOL);
    }
}
