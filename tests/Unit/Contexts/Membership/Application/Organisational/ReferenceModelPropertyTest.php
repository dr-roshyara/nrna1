<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational;

use PHPUnit\Framework\TestCase;
use Tests\Doubles\InMemoryMemberRepository;
use Tests\Unit\Contexts\Membership\Application\Organisational\Support\ReferencePropertyRun;

/**
 * Seeded property test (Slice 1a contract): handlers + in-memory repository + Slice 0
 * resolution against the independent ReferenceMembershipModel (200 seeds × 40 commands).
 * The run itself is shared with the PostgreSQL adapter (Slice 1b, T11). PBDIGIT-259.
 */
final class ReferenceModelPropertyTest extends TestCase
{
    public function test_handlers_and_repository_agree_with_the_reference_model(): void
    {
        $run = new ReferencePropertyRun();

        $failures = $run->run(
            static fn () => new InMemoryMemberRepository(static fn (): int => 1000),
            static fn (int $seed): array => ['O1', 'O2'],
            200,
            40,
        );

        $this->assertSame([], $failures);
        $this->assertGreaterThan(0, $run->stats['appended:'] ?? 0);
    }
}
