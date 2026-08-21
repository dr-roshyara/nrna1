<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Shared\Tests\Domain;

use EngineeringKnowledge\Shared\Domain\HandoffContext;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * P1 — the handoff-assurance run's provenance (Phase-1 D-5).
 *
 * A pure value object carrying the six existing-provenance fields. Guards are
 * fail-closed like every Domain VO: a blank provenance field is a report you
 * cannot attribute, and attribution is the whole point of D-5.
 */
final class HandoffContextTest extends TestCase
{
    public function test_it_carries_the_six_provenance_fields(): void
    {
        $context = HandoffContext::of(
            'docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md',
            'knowledge-lint',
            '1.0.0',
            '8307beca',
            '2026-08-21T16:00:00+00:00',
            'php scripts/knowledge-lint.php --report=handoff --document=…',
        );

        self::assertSame('docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md', $context->target());
        self::assertSame('knowledge-lint', $context->checkerName());
        self::assertSame('1.0.0', $context->checkerVersion());
        self::assertSame('8307beca', $context->sourceCommit());
        self::assertSame('2026-08-21T16:00:00+00:00', $context->generatedAt());
        self::assertSame('php scripts/knowledge-lint.php --report=handoff --document=…', $context->commandLine());
    }

    public function test_an_unknown_source_commit_is_allowed(): void
    {
        // git may be absent or the tree uncommitted — UNKNOWN is a first-class value, not a blank.
        $context = HandoffContext::of('doc.md', 'knowledge-lint', '1.0.0', 'UNKNOWN', 't', 'php …');

        self::assertSame('UNKNOWN', $context->sourceCommit());
    }

    #[DataProvider('blankFieldProvider')]
    public function test_it_rejects_a_blank_field(string $blank): void
    {
        $this->expectException(InvalidArgumentException::class);

        HandoffContext::of(
            $blank === 'target' ? ' ' : 'doc.md',
            $blank === 'checkerName' ? ' ' : 'knowledge-lint',
            $blank === 'checkerVersion' ? ' ' : '1.0.0',
            $blank === 'sourceCommit' ? ' ' : '8307beca',
            $blank === 'generatedAt' ? ' ' : 't',
            $blank === 'commandLine' ? ' ' : 'php …',
        );
    }

    public static function blankFieldProvider(): array
    {
        return [
            'target' => ['target'],
            'checkerName' => ['checkerName'],
            'checkerVersion' => ['checkerVersion'],
            'sourceCommit' => ['sourceCommit'],
            'generatedAt' => ['generatedAt'],
            'commandLine' => ['commandLine'],
        ];
    }
}
