<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\AppendOutcome;
use App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\InMemoryMemberRepository;

/** The in-memory reference adapter must satisfy the repository contract. PBDIGIT-259; E4-L PBDIGIT-264. */
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

    public function test_a_legacy_record_with_an_unstorable_valid_time_is_replayed_before_the_valid_time_is_checked(): void
    {
        // E4-L (Option E, R-1a-6): a record written before R-1a-6 existed holds a valid time that the
        // check would now refuse. That is legacy state: new writes through append() cannot create it.
        // A genuinely identical retry (the same decision, key and fingerprint) must still receive the
        // original outcome (IDEM-1 / IDEM-3), because the key lookup precedes R-1a-6.
        $repo = new InMemoryMemberRepository(static fn (): int => 100);
        $tenant = TenantId::fromString('O1');
        $member = $repo->create($tenant, MemberId::fromString('M1'), PersonRef::fromString('P1'))->member();
        $legacy = $member->decideAdmit(FactId::fromString('A1'), TimePoint::of(2_932_897), TimePoint::infinity(), Provenance::decision('registrar'));
        $original = $repo->seedPreOptionEAppend($tenant, $member->id(), $legacy, IdempotencyKey::fromString('k1'), 'fp1');

        $retry = $repo->append($tenant, $member->id(), $legacy, IdempotencyKey::fromString('k1'), 'fp1');

        $this->assertSame(AppendOutcome::AlreadyApplied, $retry->outcome());
        $this->assertEquals($original, $retry->appended(), 'exactly the original outcome');
        $this->assertCount(1, $repo->load($tenant, $member->id())->log(), 'no additional fact');
        $this->assertSame(1, $repo->load($tenant, $member->id())->version(), 'the sequence did not advance');
        $this->assertSame(1, $repo->appliedCommand($tenant, IdempotencyKey::fromString('k1'))->appended()->seq());
    }
}
