<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\TimePoint;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * Correction slice C-1: Annul and Void in the existing kernel.
 * Contract `2026-10-09-membership-correction-slice-semantic-contract-draft.md` §14.3 / §14.8
 * (approved by the PO 2026-10-10); D-COR-2, D-COR-5 (A). PBDIGIT-268.
 */
final class AnnulVoidTest extends TestCase
{
    private static function admitted(int $at = 1): MemberHistory
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', $at, null, true));

        return $h;
    }

    // K-A1 / T1: Annul(Admit) is accepted; afterwards the admission is not live and no period remains.
    public function test_annulling_an_admission_removes_its_period(): void
    {
        $h = self::admitted();
        $h->commit($h->annul('X1', 'A1'));

        $this->assertSame([], $h->member->resolve()->periods());
        $this->assertFalse($h->member->resolve()->isMember(TimePoint::of(1)));
    }

    // K-V1 / T2: Void(Admit) is accepted; afterwards the admission is not live and no period remains.
    public function test_voiding_an_admission_removes_its_period(): void
    {
        $h = self::admitted();
        $h->commit($h->void('V1', 'A1'));

        $this->assertSame([], $h->member->resolve()->periods());
        $this->assertFalse($h->member->resolve()->isMember(TimePoint::of(1)));
    }
}
