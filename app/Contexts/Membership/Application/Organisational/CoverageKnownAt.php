<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\Ports\LedgerBoundaryPort;
use App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort;
use App\Contexts\Membership\Application\Organisational\Ports\MembershipSourceOfTruthPort;
use App\Contexts\Membership\Application\Organisational\Ports\WorkingOrganisationPort;
use App\Contexts\Membership\Application\PublishedLanguage\CoverageInterval;
use App\Contexts\Membership\Application\PublishedLanguage\CoverageKnownAtAnswer;
use App\Contexts\Membership\Application\PublishedLanguage\CoverageKnownAtItem;
use App\Contexts\Membership\Application\PublishedLanguage\CoverageKnownAtRefusal;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * C2 CoverageKnownAt (CR-01 C2, D-77; C1/C2 spec §6, §11 K-2 A3; PBDIGIT-343 plan D-7, D-8). Pure PHP.
 *
 * Order: (1) the working organisation (else CROSS_ORGANISATION); (2) the source of truth (else SOURCE_NOT_LEDGER);
 * (3) the closing step, committed; (4) τ > closedThrough → BOUNDARY_NOT_CLOSED; (5) only then the facts are read,
 * and each Member is resolved by the kernel's knownAt(τ). Complete or refused, never partial. An unknown membershipId
 * or more than MAX_KEYS keys is a caller error (exception). Callers wanting "now" use the returned boundary as τ.
 */
final class CoverageKnownAt
{
    public const MAX_KEYS = 1_000;

    public function __construct(
        private readonly MemberRepositoryPort $members,
        private readonly LedgerBoundaryPort $boundary,
        private readonly MembershipSourceOfTruthPort $source,
        private readonly WorkingOrganisationPort $working,
    ) {
    }

    /** @param list<MemberId> $ids */
    public function byMembershipIds(TenantId $organisation, array $ids, Instant $tau): CoverageKnownAtAnswer
    {
        return $this->answer($organisation, $ids, $tau, function (MemberId $id) use ($organisation, $tau): CoverageKnownAtItem {
            $member = $this->members->load($organisation, $id) ?? throw new UnknownMembershipId("Unknown membershipId {$id->value()}.");

            return self::item($member, $tau, $id->value(), null);
        });
    }

    /** @param list<PersonRef> $people */
    public function byPersonRefs(TenantId $organisation, array $people, Instant $tau): CoverageKnownAtAnswer
    {
        return $this->answer($organisation, $people, $tau, function (PersonRef $person) use ($organisation, $tau): CoverageKnownAtItem {
            $member = $this->members->findByHolder($organisation, $person);

            return $member === null
                ? new CoverageKnownAtItem(null, $person->value(), 0, [], [], [])   // no membership: empty, seq 0 (spec §3)
                : self::item($member, $tau, null, $person->value());
        });
    }

    private function answer(TenantId $organisation, array $keys, Instant $tau, \Closure $resolve): CoverageKnownAtAnswer
    {
        if (count($keys) > self::MAX_KEYS) {
            throw new \InvalidArgumentException(sprintf('C2 accepts at most %d keys per call; page with the same τ.', self::MAX_KEYS));
        }
        $working = $this->working->current();
        if ($working === null || $working->value() !== $organisation->value()) {
            return CoverageKnownAtAnswer::refused(CoverageKnownAtRefusal::CrossOrganisation);
        }
        if (!$this->source->isLedger($organisation)) {
            return CoverageKnownAtAnswer::refused(CoverageKnownAtRefusal::SourceNotLedger);
        }
        $closed = $this->boundary->closeOrRead($organisation);   // committed before anything is read (A3)
        if ($closed === null || $tau->isAfter($closed)) {
            return CoverageKnownAtAnswer::refused(CoverageKnownAtRefusal::BoundaryNotClosed, $closed?->ordinal());
        }

        return CoverageKnownAtAnswer::complete($closed->ordinal(), array_map($resolve, array_values($keys)));
    }

    private static function item(Member $member, Instant $tau, ?string $membershipId, ?string $personRef): CoverageKnownAtItem
    {
        $seq = 0;
        foreach ($member->log() as $recorded) {
            if (!$recorded->recordedAt()->isAfter($tau)) {
                $seq = max($seq, $recorded->seq());
            }
        }
        $known = $member->knownAt($tau);

        return new CoverageKnownAtItem(
            $membershipId,
            $personRef,
            $seq,
            CoverageInterval::listFrom($known->coverage()),
            CoverageInterval::listFrom($known->inForceIntervals()),
            CoverageInterval::listFrom($known->suspendedIntervals()),
        );
    }
}
