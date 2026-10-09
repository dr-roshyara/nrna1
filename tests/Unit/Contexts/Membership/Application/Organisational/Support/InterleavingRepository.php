<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational\Support;

use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\AppendResult;
use App\Contexts\Membership\Application\Organisational\Ports\AppliedCommand;
use App\Contexts\Membership\Application\Organisational\Ports\CreateResult;
use App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort;
use App\Contexts\Membership\Domain\Organisational\Decision;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Deterministic simulation of a concurrent writer: runs a hook just before the
 * next append or create reaches the real repository (the other process "wins the race").
 * It also records every decision handed to append, to prove no stale decision is reused.
 */
final class InterleavingRepository implements MemberRepositoryPort
{
    /** @var list<array{decision: Decision, versionAtAppend: int}> */
    public array $appendedDecisions = [];
    /** @var list<\Closure(): void> */
    private array $beforeAppend = [];
    /** @var list<\Closure(): void> */
    private array $beforeCreate = [];
    /** @var list<\Closure(): void> */
    private array $beforeLoad = [];

    public function __construct(private readonly MemberRepositoryPort $inner)
    {
    }

    public function beforeNextAppend(\Closure $hook): void
    {
        $this->beforeAppend[] = $hook;
    }

    public function beforeNextLoad(\Closure $hook): void
    {
        $this->beforeLoad[] = $hook;
    }

    public function beforeNextCreate(\Closure $hook): void
    {
        $this->beforeCreate[] = $hook;
    }

    public function load(TenantId $tenant, MemberId $id): ?Member
    {
        if ($hook = array_shift($this->beforeLoad)) {
            $hook();
        }

        return $this->inner->load($tenant, $id);
    }

    public function findByHolder(TenantId $tenant, PersonRef $holder): ?Member
    {
        return $this->inner->findByHolder($tenant, $holder);
    }

    public function create(TenantId $tenant, MemberId $id, PersonRef $holder): CreateResult
    {
        if ($hook = array_shift($this->beforeCreate)) {
            $hook();
        }

        return $this->inner->create($tenant, $id, $holder);
    }

    public function appliedCommand(TenantId $tenant, IdempotencyKey $key): ?AppliedCommand
    {
        return $this->inner->appliedCommand($tenant, $key);
    }

    public function append(TenantId $tenant, MemberId $id, Decision $accepted, IdempotencyKey $key, string $fingerprint): AppendResult
    {
        if ($hook = array_shift($this->beforeAppend)) {
            $hook();
        }
        $this->appendedDecisions[] = ['decision' => $accepted, 'versionAtAppend' => $this->inner->load($tenant, $id)?->version() ?? -1];

        return $this->inner->append($tenant, $id, $accepted, $key, $fingerprint);
    }
}
