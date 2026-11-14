<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\AppendResult;
use App\Contexts\Membership\Application\Organisational\Ports\AppliedCommand;
use App\Contexts\Membership\Application\Organisational\Ports\CreateResult;
use App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort;
use App\Contexts\Membership\Domain\Organisational\Decision;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Platform\Infrastructure\Published\RoutedOrganisationConnection;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * The ledger repository on the ROUTED organisation's own database (PBDIGIT-345 task 10; D-233): the existing
 * PostgresMemberRepository, built per call on Platform's published RoutedOrganisationConnection for that organisation.
 * Facts and their C1 event are written there in one transaction, relayed per organisation (task 9). Any other
 * organisation, or none → NotTheRoutedOrganisation; nothing central or legacy is ever read.
 */
final class RoutedMemberRepository implements MemberRepositoryPort
{
    public function __construct(
        private readonly RoutedOrganisationConnection $connections,
        private readonly LedgerClockReader $clock,
    ) {
    }

    private function on(TenantId $tenant): PostgresMemberRepository
    {
        return new PostgresMemberRepository($this->connections->connectionFor($tenant), $this->clock);
    }

    public function load(TenantId $tenant, MemberId $id): ?Member
    {
        return $this->on($tenant)->load($tenant, $id);
    }

    public function findByHolder(TenantId $tenant, PersonRef $holder): ?Member
    {
        return $this->on($tenant)->findByHolder($tenant, $holder);
    }

    public function create(TenantId $tenant, MemberId $id, PersonRef $holder): CreateResult
    {
        return $this->on($tenant)->create($tenant, $id, $holder);
    }

    public function appliedCommand(TenantId $tenant, IdempotencyKey $key): ?AppliedCommand
    {
        return $this->on($tenant)->appliedCommand($tenant, $key);
    }

    public function append(TenantId $tenant, MemberId $id, Decision $accepted, IdempotencyKey $key, string $fingerprint): AppendResult
    {
        return $this->on($tenant)->append($tenant, $id, $accepted, $key, $fingerprint);
    }
}
