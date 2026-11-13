<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\PublicationSummary;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-5c (design §5): what one publication CONCERNS, for the feed (v3.11 §10 RevisionPublished: kinds,
 * subjects, reconciliation mappings). Pure, deterministic: sorted and distinct, so the same publication always reads
 * the same (historical reproducibility).
 */
final class PublicationSummaryTest extends TestCase
{
    private const A = '3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f';
    private const B = '8a7b6c5d-4e3f-4a1b-9c8d-7e6f5a4b3c2d';
    private const C = '6c5d4e3f-2a1b-4c9d-8e7f-6a5b4c3d2e1f';

    private static function u(string $id): ReferenceUnitId
    {
        return ReferenceUnitId::fromString($id);
    }

    public function test_kinds_and_subjects_are_distinct_and_sorted(): void
    {
        $s = PublicationSummary::of([
            [SlotKey::existence(self::u(self::B)), 'exists'],
            [SlotKey::containment(self::u(self::B), 'admin'), self::A],
            [SlotKey::existence(self::u(self::A)), 'exists'],
            [SlotKey::identifier(self::u(self::B), 'iso3166-2'), 'NP-P3'],
        ]);

        $this->assertSame(['CONTAINMENT', 'EXISTENCE', 'IDENTIFIER'], $s->kinds);
        $this->assertSame([self::A, self::B], $s->subjects);
        $this->assertSame([], $s->merges);
    }

    public function test_merges_and_their_reversals_are_reported(): void
    {
        $s = PublicationSummary::of([
            [SlotKey::reconciliation(self::u(self::C)), self::A],
            [SlotKey::reconciliation(self::u(self::B)), null],   // a reversal: NONE
        ]);

        // sorted by identity id: C (6c…) before B (8a…)
        $this->assertSame([['identity' => self::C, 'canonical' => self::A], ['identity' => self::B, 'canonical' => null]], $s->merges);
        $this->assertSame([self::C, self::B], $s->subjects);
        $this->assertSame(['RECONCILIATION'], $s->kinds);
    }

    public function test_a_slot_without_a_subject_adds_its_kind_only_and_a_notice_concerns_nothing(): void
    {
        $dispute = PublicationSummary::of([[SlotKey::dispute(ClaimId::fromString('00000000-0000-4000-8000-000000000001')), 'disputed']]);
        $this->assertSame(['DISPUTE'], $dispute->kinds);
        $this->assertSame([], $dispute->subjects);

        $notice = PublicationSummary::of([]);
        $this->assertSame([[], [], []], [$notice->kinds, $notice->subjects, $notice->merges]);
    }
}
