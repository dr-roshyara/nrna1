<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCore;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Committee\CommitteeSeatId;
use App\Contexts\Election\Domain\OperatingCore\Committee\ElectionCommittee;
use App\Contexts\Election\Domain\OperatingCore\Committee\VacancyGround;
use App\Contexts\Election\Domain\OperatingCore\Committee\VacancyReason;
use App\Contexts\Election\Domain\OperatingCore\Event\CommitteeSeatFilled;
use App\Contexts\Election\Domain\OperatingCore\Event\CommitteeSeatVacated;
use App\Contexts\Election\Domain\OperatingCore\Exception\CommitteeTooSmall;
use App\Contexts\Election\Domain\OperatingCore\Exception\SeatNotVacant;
use App\Contexts\Election\Domain\OperatingCore\Exception\UnknownCommitteeSeat;
use App\Contexts\Election\Domain\OperatingCore\Gate\RequiredVotes;
use App\Contexts\Election\Domain\OperatingCore\Policy\UnableToFunction;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;
use PHPUnit\Framework\TestCase;

/**
 * AG-1 `ElectionCommittee` — invariants I-1…I-6 and derived `unableToFunction`
 * (EM-ARCH-001 §2c; EM-GOV-033, 064, 057, 056, 065).
 * RED tests 1–6 of the EM-IMPL-001 readiness report §E.
 */
final class ElectionCommitteeTest extends TestCase
{
    private function committeeOfThree(): ElectionCommittee
    {
        return ElectionCommittee::constitute(
            ElectionId::fromString('election-1'),
            CommitteeSeatId::fromString('seat-a'),
            CommitteeSeatId::fromString('seat-b'),
            CommitteeSeatId::fromString('seat-c'),
        );
    }

    /** Test 1 — I-1: constitution with fewer than 3 members is rejected (EM-GOV-033). */
    public function test_constitution_with_fewer_than_three_seats_is_rejected(): void
    {
        $this->expectException(CommitteeTooSmall::class);

        ElectionCommittee::constitute(
            ElectionId::fromString('election-1'),
            CommitteeSeatId::fromString('seat-a'),
            CommitteeSeatId::fromString('seat-b'),
        );
    }

    /** Test 2 — I-2: vacancy arises only from a recorded VacancyEvent with one of the three closed grounds (EM-GOV-064). */
    public function test_vacancy_arises_only_from_a_recorded_vacancy_event_with_a_closed_ground(): void
    {
        $committee = $this->committeeOfThree();

        $event = $committee->recordVacancy(
            CommitteeSeatId::fromString('seat-a'),
            VacancyGround::ResignationWithReason,
            VacancyReason::fromString('personal reasons stated by the member'),
            RecordedInstant::fromEpochSeconds(1_000),
        );

        $this->assertInstanceOf(CommitteeSeatVacated::class, $event);
        $this->assertTrue($committee->isVacant(CommitteeSeatId::fromString('seat-a')));
        $this->assertSame(2, $committee->nonVacantCount());

        // Resignation requires a stated reason (EM-GOV-064: "resignation with reason").
        $this->expectException(\InvalidArgumentException::class);
        $committee->recordVacancy(
            CommitteeSeatId::fromString('seat-b'),
            VacancyGround::ResignationWithReason,
            null,
            RecordedInstant::fromEpochSeconds(1_001),
        );
    }

    /** Test 2b — I-2: a vacancy event against an unknown seat is refused. */
    public function test_vacancy_event_against_unknown_seat_is_refused(): void
    {
        $committee = $this->committeeOfThree();

        $this->expectException(UnknownCommitteeSeat::class);
        $committee->recordVacancy(
            CommitteeSeatId::fromString('seat-x'),
            VacancyGround::DeathOrPermanentIncapacity,
            null,
            RecordedInstant::fromEpochSeconds(1_000),
        );
    }

    /** Test 3 — I-2: temporary unavailability changes nothing structural — no API for it exists (EM-GOV-064; B-2). */
    public function test_temporary_unavailability_is_not_representable_as_committee_structure(): void
    {
        $committee = $this->committeeOfThree();

        // Unavailability is a fact about a decision, never a state of the Committee (design B-2):
        // the aggregate exposes no method to mark a seat unavailable.
        foreach (get_class_methods($committee) as $method) {
            $this->assertDoesNotMatchRegularExpression(
                '/unavail|absent|inactive/i',
                $method,
                'AG-1 must not model temporary unavailability as committee structure (EM-GOV-064).'
            );
        }

        // And nothing structural changed without a recorded vacancy event.
        $this->assertSame(3, $committee->nonVacantCount());
        $this->assertSame(3, $committee->constitutedSize());
    }

    /** Test 4 — I-3: the denominator is the constituted membership; vacancy does not reduce it, filling does not change it (EM-GOV-057). */
    public function test_constituted_denominator_is_stable_under_vacancy_and_filling(): void
    {
        $committee = $this->committeeOfThree();
        $this->assertSame(3, $committee->constitutedSize());

        $committee->recordVacancy(
            CommitteeSeatId::fromString('seat-a'),
            VacancyGround::LossOfEligibilityOrIndependence,
            null,
            RecordedInstant::fromEpochSeconds(1_000),
        );
        $this->assertSame(3, $committee->constitutedSize(), 'Vacancy must not reduce the denominator.');

        $committee->fillSeat(
            CommitteeSeatId::fromString('seat-a'),
            'appointee-1',
            RecordedInstant::fromEpochSeconds(2_000),
        );
        $this->assertSame(3, $committee->constitutedSize(), 'Filling must not change the denominator.');

        // Required votes for the constituted size never move: ceil(2*3/3) = 2.
        $this->assertSame(2, RequiredVotes::forConstitutedSize($committee->constitutedSize())->count());
    }

    /** Test 5 — I-4: filling re-occupies the EXISTING seat — no new membership, nothing reopened (EM-GOV-056). */
    public function test_filling_reoccupies_the_existing_seat_and_creates_no_new_membership(): void
    {
        $committee = $this->committeeOfThree();
        $committee->recordVacancy(
            CommitteeSeatId::fromString('seat-b'),
            VacancyGround::DeathOrPermanentIncapacity,
            null,
            RecordedInstant::fromEpochSeconds(1_000),
        );

        $event = $committee->fillSeat(
            CommitteeSeatId::fromString('seat-b'),
            'appointee-2',
            RecordedInstant::fromEpochSeconds(2_000),
        );

        $this->assertInstanceOf(CommitteeSeatFilled::class, $event);
        $this->assertSame('seat-b', $event->seatId->toString(), 'The replacement fills the existing seat.');
        $this->assertSame(3, $committee->constitutedSize());
        $this->assertSame(3, $committee->nonVacantCount());
        $this->assertFalse($committee->isVacant(CommitteeSeatId::fromString('seat-b')));

        // Only vacancy-filling exists: a non-vacant seat cannot be "filled" (B-2 — no reconstitution concept).
        $this->expectException(SeatNotVacant::class);
        $committee->fillSeat(
            CommitteeSeatId::fromString('seat-c'),
            'appointee-3',
            RecordedInstant::fromEpochSeconds(3_000),
        );
    }

    /** Test 6 — P-3: `unableToFunction` is derived arithmetic (non-vacant < required), never independently settable (EM-GOV-065). */
    public function test_unable_to_function_is_derived_arithmetic_and_not_settable(): void
    {
        $committee = $this->committeeOfThree();
        $required = RequiredVotes::forConstitutedSize(3); // 2

        $this->assertFalse(UnableToFunction::evaluate($committee->nonVacantCount(), $required));
        $this->assertFalse($committee->unableToFunction($required));

        $committee->recordVacancy(
            CommitteeSeatId::fromString('seat-a'),
            VacancyGround::ResignationWithReason,
            VacancyReason::fromString('stated reason'),
            RecordedInstant::fromEpochSeconds(1_000),
        );
        // 2 non-vacant, 2 required: still able.
        $this->assertFalse($committee->unableToFunction($required));

        $committee->recordVacancy(
            CommitteeSeatId::fromString('seat-b'),
            VacancyGround::DeathOrPermanentIncapacity,
            null,
            RecordedInstant::fromEpochSeconds(1_100),
        );
        // 1 non-vacant < 2 required: unable — purely derived.
        $this->assertTrue($committee->unableToFunction($required));

        // No declaration, no determiner, no classifier: nothing on the aggregate can SET the condition.
        foreach (get_class_methods($committee) as $method) {
            $this->assertDoesNotMatchRegularExpression(
                '/^(set|mark|declare).*(unable|inoperative)/i',
                $method,
                'unableToFunction must be derived, never stored or declared (EM-GOV-065).'
            );
        }
    }
}
