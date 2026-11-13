<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-1, cycle 3 (v3.11 §2 I-4, §3):
 * - a claim is an insert-only assertion about ONE slot: value, valid interval [from, to), basis;
 * - a withdrawal is a claim with value none in exactly the target's slot;
 * - every establish claim (EXISTENCE with a value) cites an establishing instrument or a duplicate-search
 *   assessment (I-4);
 * - claims in one slot supersede each other, only for the valid time they cover; the later publication position wins;
 * - claims in different slots never interact;
 * - knowledge "as at position p" is never rewritten by later claims.
 */
final class SupersessionTest extends TestCase
{
    private const UNIT = '3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f';
    private const PARENT_A = '8a7b6c5d-4e3f-4a1b-9c8d-7e6f5a4b3c2d';
    private const PARENT_B = '6c5d4e3f-2a1b-4c9d-8e7f-6a5b4c3d2e1f';

    private static int $n = 0;

    private static function id(): ClaimId
    {
        self::$n++;

        return ClaimId::fromString(sprintf('00000000-0000-4000-8000-%012d', self::$n));
    }

    private static function d(string $date): ValidDate
    {
        return ValidDate::fromString($date);
    }

    private static function slot(): SlotKey
    {
        return SlotKey::containment(ReferenceUnitId::fromString(self::UNIT), 'admin');
    }

    private static function parent(string $parent, string $from, ?string $to = null): Claim
    {
        return new Claim(self::id(), self::slot(), ClaimValue::of($parent), new ValidInterval(self::d($from), $to === null ? null : self::d($to)), new Basis(BasisKind::Instrument, 'act-2017'));
    }

    public function test_a_later_claim_supersedes_only_for_the_valid_time_it_covers(): void
    {
        $h = SlotHistory::for(self::slot())
            ->with(self::parent(self::PARENT_A, '2015-09-20'), 1)
            ->with(self::parent(self::PARENT_B, '2017-03-10'), 2);

        $this->assertSame(self::PARENT_A, $h->resolve(self::d('2016-01-01'))?->value(), 'before 2017-03-10, B does not reach: A stands');
        $this->assertSame(self::PARENT_B, $h->resolve(self::d('2017-03-10'))?->value(), 'from its start, B supersedes A');
        $this->assertNull($h->resolve(self::d('2015-09-19')), 'nothing claimed before 2015-09-20');
    }

    public function test_the_later_position_wins_not_the_later_date(): void
    {
        $h = SlotHistory::for(self::slot())
            ->with(self::parent(self::PARENT_B, '2017-03-10'), 1)
            ->with(self::parent(self::PARENT_A, '2015-09-20'), 2);   // published later, covers more

        $this->assertSame(self::PARENT_A, $h->resolve(self::d('2018-01-01'))?->value(), 'the later publication supersedes where it covers');
    }

    public function test_a_withdrawal_is_value_none_in_the_targets_slot(): void
    {
        $withdrawal = new Claim(self::id(), self::slot(), ClaimValue::none(), new ValidInterval(self::d('2016-01-01'), self::d('2017-01-01')), new Basis(BasisKind::Other, 'correction-17'));
        $h = SlotHistory::for(self::slot())->with(self::parent(self::PARENT_A, '2015-09-20'), 1)->with($withdrawal, 2);

        $this->assertNull($h->resolve(self::d('2016-06-01')), 'withdrawn over its interval');
        $this->assertSame(self::PARENT_A, $h->resolve(self::d('2017-06-01'))?->value(), 'outside it, the earlier claim stands');
        $this->assertTrue($h->isWithdrawnAt(self::d('2016-06-01')));
        $this->assertFalse($h->isWithdrawnAt(self::d('2015-01-01')), 'never claimed is not withdrawn');
    }

    public function test_knowledge_as_at_a_position_is_never_rewritten(): void
    {
        $h1 = SlotHistory::for(self::slot())->with(self::parent(self::PARENT_A, '2015-09-20'), 1);
        $h2 = $h1->with(self::parent(self::PARENT_B, '2015-09-20'), 2);

        $this->assertSame(self::PARENT_A, $h2->resolve(self::d('2016-01-01'), upToPosition: 1)?->value(), 'as known at position 1');
        $this->assertSame(self::PARENT_B, $h2->resolve(self::d('2016-01-01'))?->value(), 'as known now');
        $this->assertSame(self::PARENT_A, $h1->resolve(self::d('2016-01-01'))?->value(), 'the earlier history value is unchanged (immutable)');
    }

    public function test_claims_in_different_slots_never_interact(): void
    {
        $classification = new Claim(self::id(), SlotKey::classification(ReferenceUnitId::fromString(self::UNIT), 'admin'), ClaimValue::of('district'), new ValidInterval(self::d('2015-09-20')), new Basis(BasisKind::Instrument, 'act'));

        $this->expectException(\InvalidArgumentException::class);
        SlotHistory::for(self::slot())->with($classification, 1);
    }

    public function test_a_dispute_cannot_enter_the_disputed_claims_slot(): void
    {
        $target = self::parent(self::PARENT_A, '2015-09-20');
        $dispute = new Claim(self::id(), SlotKey::dispute($target->id), ClaimValue::of('disputed'), new ValidInterval(self::d('2015-09-20')), new Basis(BasisKind::Other, 'assessment-1'));

        $this->expectException(\InvalidArgumentException::class);
        SlotHistory::for(self::slot())->with($target, 1)->with($dispute, 2);
    }

    public function test_a_position_is_used_once_and_must_be_positive(): void
    {
        $h = SlotHistory::for(self::slot())->with(self::parent(self::PARENT_A, '2015-09-20'), 1);
        foreach ([1, 0, -3] as $position) {
            try {
                $h->with(self::parent(self::PARENT_B, '2016-01-01'), $position);
                $this->fail("position {$position} must be refused");
            } catch (\InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_an_establish_claim_needs_an_instrument_or_a_duplicate_search_assessment(): void
    {
        $unit = SlotKey::existence(ReferenceUnitId::fromString(self::UNIT));
        $since = new ValidInterval(self::d('2015-09-20'));

        $this->assertSame('exists', (new Claim(self::id(), $unit, ClaimValue::of('exists'), $since, new Basis(BasisKind::Instrument, 'constitution-2015-schedule-4')))->value->value());
        $this->assertSame('exists', (new Claim(self::id(), $unit, ClaimValue::of('exists'), $since, new Basis(BasisKind::DuplicateSearchAssessment, 'dup-search-12')))->value->value());
        $this->assertTrue((new Claim(self::id(), $unit, ClaimValue::none(), $since, new Basis(BasisKind::Other, 'correction')))->value->isNone(), 'a withdrawal of existence is not an establish claim');

        $this->expectException(\InvalidArgumentException::class);
        new Claim(self::id(), $unit, ClaimValue::of('exists'), $since, new Basis(BasisKind::Other, 'hearsay'));
    }

    public function test_a_basis_needs_its_reference_and_a_value_is_never_blank(): void
    {
        foreach ([fn () => new Basis(BasisKind::Instrument, ' '), fn () => ClaimValue::of('')] as $bad) {
            try {
                $bad();
                $this->fail('must be refused');
            } catch (\InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
    }
}
