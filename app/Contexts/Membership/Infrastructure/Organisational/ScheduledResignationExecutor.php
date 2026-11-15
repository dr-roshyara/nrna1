<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\AppendOutcome;
use App\Contexts\Membership\Application\Organisational\Ports\DeclarationWriteOutcome;
use App\Contexts\Membership\Application\Organisational\Ports\OrganisationCalendar;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\ScheduledResignation;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\Connection;

/**
 * The D-209 closing step (a): execute every scheduled resignation that is due (PBDIGIT-354 S2-4c; D-204, D-205 (2),
 * D-223, D-239; M-TDD-01 §5.2; MI-3, MI-10, MI-15, LC-4). Runs in LedgerClockCloser's transaction on $db, which already
 * holds the organisation's clock row, BEFORE closedThrough = $t is written:
 *
 * 1. the organisation-local date of the close's own reading: `OrganisationCalendar::localDateAt($t)` (D-239);
 * 2. the `scheduled` declarations with effective date ≤ that date, locked (a concurrent withdrawal waits and loses);
 * 3. for each: the Member decides the End (`decideScheduledResignationEnd`: effective = the scheduled date, decided =
 *    the declaration, never before its date); it is appended with recordedAt = $t, then the declaration is marked
 *    executed, naming that End;
 *    - the period already ended or no longer exists → the declaration is SUPERSEDED with the reason (MI-3);
 * 4. anything else THROWS: the close rolls back as a whole and closedThrough stays (MI-10, LC-4).
 *
 * Fact id, key and fingerprint derive from the declaration, so a re-run after a rollback writes the same End.
 * Production binding: none yet. The production calendar comes from Organisation (S4); until then the closer keeps
 * NoDueScheduledResignations (D-239).
 */
final class ScheduledResignationExecutor implements DueScheduledResignations
{
    public function __construct(private readonly OrganisationCalendar $calendar)
    {
    }

    public function executeDue(Connection $db, TenantId $organisation, Instant $t): void
    {
        $today = $this->calendar->localDateAt($organisation, $t);
        $store = new PostgresScheduledResignationStore($db);
        $members = new PostgresMemberRepository($db, new PostgresLedgerClockReader());   // the reader is never used here: the close's $t is

        foreach ($store->lockDue($organisation, $today) as [$memberId, $declaration]) {
            $ref = $declaration->id()->value();
            $member = $members->load($organisation, $memberId)
                ?? throw new \RuntimeException("Scheduled resignation {$ref}: its member {$memberId->value()} is missing.");
            $endId = FactId::fromString("scheduled-resignation:{$ref}");
            $decision = $member->decideScheduledResignationEnd($endId, $declaration, $today);

            if (!$decision->isAccepted()) {
                $reason = $decision->refusal();
                if ($reason !== RefusalReason::PeriodAlreadyEnded && $reason !== RefusalReason::PeriodNotFound) {
                    throw new \RuntimeException("Scheduled resignation {$ref} cannot be executed: {$reason->value}.");
                }
                $superseded = self::changed($declaration->supersede("the period ended before the scheduled date ({$reason->value})"), $ref);
                self::stored($store->transition($organisation, $memberId, $superseded, IdempotencyKey::fromString("supersede-scheduled-resignation:{$ref}"), "supersede-scheduled-resignation:{$ref}"), $ref);
                continue;
            }

            $appended = $members->appendWithinClose($organisation, $memberId, $decision, IdempotencyKey::fromString("execute-scheduled-resignation:{$ref}"), "execute-scheduled-resignation:{$ref}", $t);
            if ($appended->outcome() !== AppendOutcome::Appended) {
                throw new \RuntimeException("Scheduled resignation {$ref}: its End was not recorded ({$appended->outcome()->value}).");
            }
            $executed = self::changed($declaration->markExecuted($endId), $ref);
            self::stored($store->transition($organisation, $memberId, $executed, IdempotencyKey::fromString("executed-scheduled-resignation:{$ref}"), "executed-scheduled-resignation:{$ref}"), $ref);
        }
    }

    private static function changed(ScheduledResignation|RefusalReason $result, string $ref): ScheduledResignation
    {
        return $result instanceof ScheduledResignation
            ? $result
            : throw new \RuntimeException("Scheduled resignation {$ref}: its state cannot change ({$result->value}).");
    }

    private static function stored(DeclarationWriteOutcome $outcome, string $ref): void
    {
        if ($outcome !== DeclarationWriteOutcome::Written) {
            throw new \RuntimeException("Scheduled resignation {$ref}: the declaration's new state was not stored.");
        }
    }
}
