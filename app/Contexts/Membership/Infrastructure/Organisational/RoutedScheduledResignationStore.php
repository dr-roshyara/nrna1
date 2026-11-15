<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\AppliedDeclarationRequest;
use App\Contexts\Membership\Application\Organisational\Ports\DeclarationWriteOutcome;
use App\Contexts\Membership\Application\Organisational\Ports\OrganisationCalendar;
use App\Contexts\Membership\Application\Organisational\Ports\ScheduledResignationStorePort;
use App\Contexts\Membership\Domain\Organisational\DeclarationId;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\ScheduledResignation;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Platform\Infrastructure\Published\RoutedOrganisationConnection;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * The declaration store on the ROUTED organisation's own database (PBDIGIT-354 S2-4d; D-233, as RoutedMemberRepository):
 * PostgresScheduledResignationStore built per call on Platform's published RoutedOrganisationConnection. Any other
 * organisation, or none → NotTheRoutedOrganisation; nothing central or legacy is read.
 *
 * **It needs the OrganisationCalendar (D-239)**, so in production, where nothing binds the calendar until Organisation
 * S4, the container cannot build it, and so cannot build the schedule / withdraw use cases (fail closed), independently
 * of the unbound authority (Governance S3).
 */
final class RoutedScheduledResignationStore implements ScheduledResignationStorePort
{
    public function __construct(
        private readonly RoutedOrganisationConnection $connections,
        private readonly LedgerClockReader $clock,
        private readonly OrganisationCalendar $calendar,
    ) {
    }

    private function on(TenantId $tenant): PostgresScheduledResignationStore
    {
        return new PostgresScheduledResignationStore($this->connections->connectionFor($tenant), $this->clock, $this->calendar);
    }

    public function forMember(TenantId $tenant, MemberId $member): array
    {
        return $this->on($tenant)->forMember($tenant, $member);
    }

    public function find(TenantId $tenant, MemberId $member, DeclarationId $id): ?ScheduledResignation
    {
        return $this->on($tenant)->find($tenant, $member, $id);
    }

    public function appliedRequest(TenantId $tenant, IdempotencyKey $key): ?AppliedDeclarationRequest
    {
        return $this->on($tenant)->appliedRequest($tenant, $key);
    }

    public function record(TenantId $tenant, MemberId $member, int $basedOnVersion, ScheduledResignation $scheduled, IdempotencyKey $key, string $fingerprint): DeclarationWriteOutcome
    {
        return $this->on($tenant)->record($tenant, $member, $basedOnVersion, $scheduled, $key, $fingerprint);
    }

    public function withdraw(TenantId $tenant, MemberId $member, DeclarationId $id, TimePoint $declaredOn, Provenance $by, IdempotencyKey $key, string $fingerprint): DeclarationWriteOutcome|RefusalReason
    {
        return $this->on($tenant)->withdraw($tenant, $member, $id, $declaredOn, $by, $key, $fingerprint);
    }

    public function transition(TenantId $tenant, MemberId $member, ScheduledResignation $changed, IdempotencyKey $key, string $fingerprint): DeclarationWriteOutcome
    {
        return $this->on($tenant)->transition($tenant, $member, $changed, $key, $fingerprint);
    }
}
