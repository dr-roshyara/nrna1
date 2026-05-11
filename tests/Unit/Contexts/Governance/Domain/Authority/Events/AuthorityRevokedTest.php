<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\Authority\Events;

use App\Contexts\Governance\Domain\Authority\Events\AuthorityRevoked;
use App\Contexts\Governance\Domain\Authority\ValueObjects\AuthorityAssignmentId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class AuthorityRevokedTest extends TestCase
{
    private DateTimeImmutable $revokedAt;

    protected function setUp(): void
    {
        $this->revokedAt = new DateTimeImmutable('2026-05-09T15:00:00Z');
    }

    private function makeEvent(): AuthorityRevoked
    {
        return AuthorityRevoked::from(
            assignmentId: AuthorityAssignmentId::from('assignment-uuid-222'),
            tenantId: TenantId::fromString('org-nrna-eu'),
            revokedBy: MemberId::from('president-123'),
            reason: 'Constitutional order withdrawn',
            revokedAt: $this->revokedAt,
        );
    }

    public function test_carries_all_required_fields(): void
    {
        $event = $this->makeEvent();

        $this->assertTrue($event->assignmentId()->equals(AuthorityAssignmentId::from('assignment-uuid-222')));
        $this->assertTrue($event->tenantId()->equals(TenantId::fromString('org-nrna-eu')));
        $this->assertTrue($event->revokedBy()->equals(MemberId::from('president-123')));
        $this->assertSame('Constitutional order withdrawn', $event->reason());
        $this->assertSame($this->revokedAt, $event->revokedAt());
    }

    public function test_is_readonly(): void
    {
        $reflection = new \ReflectionClass(AuthorityRevoked::class);

        foreach ($reflection->getProperties() as $property) {
            $this->assertTrue(
                $property->isReadOnly(),
                "Property {$property->getName()} must be readonly"
            );
        }
    }

    public function test_has_private_constructor(): void
    {
        $reflection = new \ReflectionClass(AuthorityRevoked::class);
        $this->assertTrue($reflection->getConstructor()->isPrivate());
    }

    public function test_has_no_public_setters(): void
    {
        $reflection = new \ReflectionClass(AuthorityRevoked::class);
        $setters = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            fn($m) => preg_match('/^set/', $m->getName())
        );

        $this->assertEmpty($setters);
    }
}
