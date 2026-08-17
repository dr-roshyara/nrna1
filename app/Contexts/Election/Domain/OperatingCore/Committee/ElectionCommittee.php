<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Committee;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Event\CommitteeSeatFilled;
use App\Contexts\Election\Domain\OperatingCore\Event\CommitteeSeatVacated;
use App\Contexts\Election\Domain\OperatingCore\Exception\CommitteeTooSmall;
use App\Contexts\Election\Domain\OperatingCore\Exception\UnknownCommitteeSeat;
use App\Contexts\Election\Domain\OperatingCore\Gate\RequiredVotes;
use App\Contexts\Election\Domain\OperatingCore\Policy\UnableToFunction;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;
use InvalidArgumentException;

/**
 * AG-1 `ElectionCommittee` — the constituted body (EM-ARCH-001 §2c).
 *
 * Invariants owned:
 *  I-1 constituted size ≥ 3 at constitution (EM-GOV-033)
 *  I-2 a seat becomes vacant ONLY by a recorded vacancy event with a closed ground;
 *      temporary unavailability changes nothing structural (EM-GOV-064)
 *  I-3 the acceptance denominator is the CONSTITUTED membership — vacancy does not
 *      reduce it, filling does not change it (EM-GOV-057)
 *  I-4 filling re-occupies the existing seat; no new membership, nothing reopened,
 *      no decision altered (EM-GOV-056)
 *  I-5 the Chief and the Committee have no filling power — filling arrives only
 *      through the external-authority port; this aggregate offers no other route
 *      (EM-GOV-028, 056; B-3)
 *  I-6 member ⇥ candidate-representative incompatibility (EM-GOV-029) — vacuous in
 *      Model A (no Representation Vote exists), kept as a wall by NOT modelling any
 *      representative linkage here.
 *
 * Derived, never stored: unableToFunction = non-vacant seats < required Committee
 * Votes (EM-GOV-065 — arithmetic, no declaration, no determiner).
 */
final class ElectionCommittee
{
    /** @var array<string, CommitteeSeat> keyed by seat id */
    private array $seats = [];

    private function __construct(private readonly ElectionId $electionId, CommitteeSeatId ...$seatIds)
    {
        if (count($seatIds) < 3) {
            throw CommitteeTooSmall::withSize(count($seatIds));
        }
        foreach ($seatIds as $seatId) {
            if (isset($this->seats[$seatId->toString()])) {
                throw new InvalidArgumentException(sprintf('Duplicate Committee seat "%s".', $seatId->toString()));
            }
            $this->seats[$seatId->toString()] = new CommitteeSeat($seatId);
        }
    }

    /** Constitution happens ONCE, during Election Appointment (EM-GOV-026, 056). */
    public static function constitute(ElectionId $electionId, CommitteeSeatId ...$seatIds): self
    {
        return new self($electionId, ...$seatIds);
    }

    public function electionId(): ElectionId
    {
        return $this->electionId;
    }

    /** I-3: the denominator — fixed at constitution, moved by nothing (EM-GOV-057). */
    public function constitutedSize(): int
    {
        return count($this->seats);
    }

    public function nonVacantCount(): int
    {
        return count(array_filter($this->seats, static fn (CommitteeSeat $seat) => ! $seat->isVacant()));
    }

    /** @return list<CommitteeSeatId> */
    public function vacantSeatIds(): array
    {
        return array_values(array_map(
            static fn (CommitteeSeat $seat) => $seat->id(),
            array_filter($this->seats, static fn (CommitteeSeat $seat) => $seat->isVacant()),
        ));
    }

    public function isVacant(CommitteeSeatId $seatId): bool
    {
        return $this->seat($seatId)->isVacant();
    }

    /** I-2: the recorded vacancy event — the ONLY thing that vacates a seat (EM-GOV-064). */
    public function recordVacancy(
        CommitteeSeatId $seatId,
        VacancyGround $ground,
        ?VacancyReason $reason,
        RecordedInstant $recordedAt,
    ): CommitteeSeatVacated {
        if ($ground === VacancyGround::ResignationWithReason && $reason === null) {
            throw new InvalidArgumentException('Resignation requires a stated reason (EM-GOV-064).');
        }

        $this->seat($seatId)->vacate();

        return new CommitteeSeatVacated($this->electionId, $seatId, $ground, $reason, $recordedAt);
    }

    /**
     * I-4/I-5: fills the EXISTING seat. This act arrives only through the external
     * authority's future adapter (EM-GOV-028, 056; B-3 — no internal caller exists;
     * the aggregate cannot verify the caller, the missing adapter guarantees it).
     */
    public function fillSeat(CommitteeSeatId $seatId, string $appointeeReference, RecordedInstant $recordedAt): CommitteeSeatFilled
    {
        if (trim($appointeeReference) === '') {
            throw new InvalidArgumentException('An appointee reference cannot be blank.');
        }

        $this->seat($seatId)->fill();

        return new CommitteeSeatFilled($this->electionId, $seatId, $appointeeReference, $recordedAt);
    }

    /** Derived arithmetic, never a stored flag (EM-GOV-065; P-3). */
    public function unableToFunction(RequiredVotes $required): bool
    {
        return UnableToFunction::evaluate($this->nonVacantCount(), $required);
    }

    private function seat(CommitteeSeatId $seatId): CommitteeSeat
    {
        return $this->seats[$seatId->toString()]
            ?? throw UnknownCommitteeSeat::withId($seatId->toString());
    }
}
