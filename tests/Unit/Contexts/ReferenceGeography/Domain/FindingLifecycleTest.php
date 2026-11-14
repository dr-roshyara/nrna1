<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\ApprovalStatusKind;
use App\Contexts\ReferenceGeography\Domain\FindingLifecycle;
use App\Contexts\ReferenceGeography\Domain\GovernanceNotice;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-6c-2 (design revision 3 §8, §10; accepted 2026-11-14): ONE definition of an open finding and of the
 * approval status, at any knowledge position k. A finding f is open at k iff f ≤ k and no confirmation of f, no
 * closure of f and no published correction q of the publication with f < q ≤ k. A later entry never changes an earlier
 * answer. Tests B1, B3 (domain part), B10 (domain part).
 */
final class FindingLifecycleTest extends TestCase
{
    private static function p(string $who): PersonReference
    {
        return PersonReference::fromString(md5('person:' . $who));
    }

    public function test_b1_a_finding_is_open_until_resolved_and_only_from_its_own_position(): void
    {
        $lc = FindingLifecycle::of(5, [[9, GovernanceNotice::finding(5, ['m1'], 3)]], []);

        $this->assertNull($lc->openFindingAt(8), 'not yet known at 8');
        $this->assertSame(9, $lc->openFindingAt(9));
        $this->assertSame(9, $lc->openFindingAt(null));
    }

    public function test_each_resolution_closes_the_finding_from_its_position_on(): void
    {
        $confirmed = FindingLifecycle::of(5, [[9, GovernanceNotice::finding(5, ['m1'], 3)], [12, GovernanceNotice::confirmation(9, self::p('Y'), 'd', 4)]], []);
        $closed = FindingLifecycle::of(5, [[9, GovernanceNotice::finding(5, ['m1'], 3)], [12, GovernanceNotice::closure(9, 'reversal:m1', 4)]], []);
        $corrected = FindingLifecycle::of(5, [[9, GovernanceNotice::finding(5, ['m1'], 3)]], [12]);

        foreach (['confirmed' => $confirmed, 'closed' => $closed, 'corrected' => $corrected] as $how => $lc) {
            $this->assertSame(9, $lc->openFindingAt(11), "{$how}: still open at 11");
            $this->assertNull($lc->openFindingAt(12), "{$how}: resolved at 12");
        }
    }

    public function test_status_at_each_knowledge_position_is_reproducible(): void
    {
        $lc = FindingLifecycle::of(5, [
            [9, GovernanceNotice::finding(5, ['m1'], 3)],
            [12, GovernanceNotice::closure(9, 'reversal:m1', 4)],
            [15, GovernanceNotice::finding(5, ['m2'], 5)],
            [18, GovernanceNotice::confirmation(15, self::p('Y'), 'd', 6)],
        ], [21]);

        $this->assertSame(ApprovalStatusKind::Approved, $lc->statusAt(8)->kind);
        $this->assertSame(ApprovalStatusKind::ApprovalDefective, $lc->statusAt(9)->kind);
        $this->assertSame(ApprovalStatusKind::FindingClosed, $lc->statusAt(12)->kind);
        $this->assertSame(ApprovalStatusKind::ApprovalDefective, $lc->statusAt(15)->kind, 'a new episode');
        $this->assertSame(ApprovalStatusKind::Confirmed, $lc->statusAt(18)->kind);
        $this->assertSame([ApprovalStatusKind::Corrected, 21], [$lc->statusAt(21)->kind, $lc->statusAt(21)->correctedAt]);
        $this->assertSame([9, 12, 15, 18, 21], array_column($lc->statusAt(21)->history, 'position'), 'every episode kept');
        $this->assertSame([9, 12], array_column($lc->statusAt(12)->history, 'position'), 'never an entry after k');
    }

    public function test_the_counted_approver_is_the_original_before_and_the_confirmer_after_a_confirmation(): void
    {
        $lc = FindingLifecycle::of(5, [[9, GovernanceNotice::finding(5, ['m1'], 3)], [12, GovernanceNotice::confirmation(9, self::p('Y'), 'd', 4)]], []);

        $this->assertTrue($lc->countedApproverAt(self::p('X'), 11)->equals(self::p('X')));
        $this->assertTrue($lc->countedApproverAt(self::p('X'), 12)->equals(self::p('Y')));
    }

    public function test_entries_of_another_publication_are_refused(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        FindingLifecycle::of(5, [[9, GovernanceNotice::finding(6, ['m1'], 3)]], []);
    }
}
