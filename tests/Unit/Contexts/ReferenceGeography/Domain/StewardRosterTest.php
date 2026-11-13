<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\RosterEntryKind;
use App\Contexts\ReferenceGeography\Domain\RosterRefusal;
use App\Contexts\ReferenceGeography\Domain\RosterRefused;
use App\Contexts\ReferenceGeography\Domain\StewardRoster;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-3a (v3.11 §8 "Roster and publication ordering", "The D-84 threshold, normative"; D-84, D-84a, D-89):
 * - the roster is append-only; every write takes the next gap-free roster version (local to Geography);
 * - authority is judged AT a roster version: appointed at v ⇔ an appointment ≤ v not ended ≤ v;
 * - a matter reviewer has authority for one revision only, and is not a steward appointment (does not set the flag);
 * - `secondStewardEverAppointed` is set by the write that brings the appointed stewards to two, recorded with that
 *   version, and never unset (D-89).
 */
final class StewardRosterTest extends TestCase
{
    private static function p(string $c): PersonReference
    {
        return PersonReference::fromString(str_repeat($c, 32));
    }

    private static function r(string $c): RevisionId
    {
        return RevisionId::fromString(str_repeat($c, 8) . '-' . str_repeat($c, 4) . '-4' . str_repeat($c, 3) . '-8' . str_repeat($c, 3) . '-' . str_repeat($c, 12));
    }

    private function assertRefused(RosterRefusal $reason, callable $act): void
    {
        try {
            $act();
            $this->fail("Expected refusal {$reason->name}");
        } catch (RosterRefused $e) {
            $this->assertSame($reason, $e->reason);
        }
    }

    public function test_every_write_takes_the_next_gap_free_version_and_records_who_made_it(): void
    {
        $roster = StewardRoster::empty();
        $owner = self::p('0');

        $this->assertSame(0, $roster->version());
        $this->assertSame(1, $roster->appointSteward(self::p('a'), $owner));
        $this->assertSame(2, $roster->appointMatterReviewer(self::p('c'), self::r('9'), $owner));
        $this->assertSame(3, $roster->endSteward(self::p('a'), $owner));
        $this->assertSame([RosterEntryKind::StewardAppointed, RosterEntryKind::MatterReviewerAppointed, RosterEntryKind::StewardEnded], array_map(static fn ($e) => $e->kind, $roster->entries()));
        $this->assertTrue($roster->entries()[0]->by->equals($owner));
        $this->assertSame([1, 2, 3], array_map(static fn ($e) => $e->version, $roster->entries()));
    }

    public function test_authority_is_judged_at_a_roster_version(): void
    {
        $roster = StewardRoster::empty();
        $roster->appointSteward(self::p('a'), self::p('0'));   // v1
        $roster->appointSteward(self::p('b'), self::p('0'));   // v2
        $roster->endSteward(self::p('a'), self::p('0'));       // v3

        $this->assertFalse($roster->isStewardAt(self::p('a'), 0));
        $this->assertTrue($roster->isStewardAt(self::p('a'), 1));
        $this->assertTrue($roster->isStewardAt(self::p('a'), 2), 'an act given at v2 stands though the appointment ended at v3');
        $this->assertFalse($roster->isStewardAt(self::p('a'), 3));
        $this->assertTrue($roster->isStewardAt(self::p('b'), 3));
        $this->assertRefused(RosterRefusal::UnknownVersion, fn () => $roster->isStewardAt(self::p('b'), 4));
    }

    public function test_a_matter_reviewer_has_authority_for_that_revision_only_and_is_no_steward(): void
    {
        $roster = StewardRoster::empty();
        $roster->appointSteward(self::p('a'), self::p('0'));
        $roster->appointMatterReviewer(self::p('c'), self::r('9'), self::p('0'));

        $this->assertTrue($roster->mayActForAt(self::p('c'), self::r('9'), 2));
        $this->assertFalse($roster->mayActForAt(self::p('c'), self::r('8'), 2), 'another matter');
        $this->assertFalse($roster->isStewardAt(self::p('c'), 2));
        $this->assertTrue($roster->mayActForAt(self::p('a'), self::r('8'), 2), 'a steward may act for any matter');
        $this->assertFalse($roster->secondStewardEverAppointedAt(2), 'a matter reviewer does not set the flag (v3.11 §8 case 5)');
    }

    public function test_the_threshold_latches_at_the_second_concurrent_appointment_and_never_unsets(): void
    {
        $roster = StewardRoster::empty();
        $roster->appointSteward(self::p('a'), self::p('0'));   // v1
        $roster->appointSteward(self::p('b'), self::p('0'));   // v2: two appointed
        $roster->endSteward(self::p('b'), self::p('0'));       // v3

        $this->assertFalse($roster->secondStewardEverAppointedAt(1));
        $this->assertTrue($roster->secondStewardEverAppointedAt(2));
        $this->assertTrue($roster->secondStewardEverAppointedAt(3), 'D-89: permanently');
        $this->assertSame(2, $roster->thresholdActivatedAt());
    }

    public function test_a_replaced_sole_steward_is_not_a_second_steward(): void
    {
        $roster = StewardRoster::empty();
        $roster->appointSteward(self::p('a'), self::p('0'));
        $roster->endSteward(self::p('a'), self::p('0'));
        $roster->appointSteward(self::p('b'), self::p('0'));

        $this->assertFalse($roster->secondStewardEverAppointedAt(3), 'never two appointed at once');
        $this->assertNull($roster->thresholdActivatedAt());
    }

    public function test_impossible_writes_are_refused_and_write_nothing(): void
    {
        $roster = StewardRoster::empty();
        $roster->appointSteward(self::p('a'), self::p('0'));

        $this->assertRefused(RosterRefusal::AlreadyAppointed, fn () => $roster->appointSteward(self::p('a'), self::p('0')));
        $this->assertRefused(RosterRefusal::NotAppointed, fn () => $roster->endSteward(self::p('b'), self::p('0')));
        $this->assertSame(1, $roster->version(), 'a refused write takes no version');
    }
}
