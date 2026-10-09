<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\InMemoryMemberRepository;

/** The in-memory reference adapter must satisfy the repository contract. PBDIGIT-259. */
final class InMemoryMemberRepositoryTest extends TestCase
{
    use MemberRepositoryContract;

    protected function repository(\Closure $clock): MemberRepositoryPort
    {
        return new InMemoryMemberRepository($clock);
    }

    protected function tenantFor(string $label): TenantId
    {
        return TenantId::fromString($label);
    }
}
