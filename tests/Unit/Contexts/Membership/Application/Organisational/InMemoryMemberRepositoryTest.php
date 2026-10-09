<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort;
use Tests\Doubles\InMemoryMemberRepository;

/** The in-memory reference adapter must satisfy the repository contract. PBDIGIT-259. */
final class InMemoryMemberRepositoryTest extends MemberRepositoryContract
{
    protected function repository(\Closure $clock): MemberRepositoryPort
    {
        return new InMemoryMemberRepository($clock);
    }
}
