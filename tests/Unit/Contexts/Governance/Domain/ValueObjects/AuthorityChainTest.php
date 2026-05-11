<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\ValueObjects;

use App\Contexts\Governance\Domain\ValueObjects\AuthorityChain;
use App\Contexts\Governance\Domain\ValueObjects\AuthorityPath;
use App\Contexts\Governance\Domain\ValueObjects\GovernanceRole;
use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use DateTimeImmutable;
use DomainException;
use PHPUnit\Framework\TestCase;

final class AuthorityChainTest extends TestCase
{
    public function test_it_captures_authority_snapshot_with_factory(): void
    {
        $memberId = MemberId::from('user-123');
        $role = GovernanceRole::COUNTRY_PRESIDENT;
        $path = AuthorityPath::fromArray(['ICC', 'CONTINENT', 'COUNTRY']);
        $capturedAt = new DateTimeImmutable('2026-01-01 10:00:00');

        $chain = AuthorityChain::capture(
            actorId: $memberId,
            role: $role,
            delegationPath: $path,
            capturedAt: $capturedAt
        );

        $this->assertTrue($chain->actorId()->equals($memberId));
        $this->assertSame($role, $chain->role());
        $this->assertTrue($chain->delegationPath()->equals($path));
        $this->assertSame($capturedAt, $chain->capturedAt());
    }

    public function test_it_derives_authority_level_from_path_length(): void
    {
        $path = AuthorityPath::fromArray(['ICC', 'CONTINENT', 'COUNTRY']);
        $chain = AuthorityChain::capture(
            actorId: MemberId::from('user-123'),
            role: GovernanceRole::COUNTRY_PRESIDENT,
            delegationPath: $path,
            capturedAt: new DateTimeImmutable()
        );

        $this->assertEquals(3, $chain->authorityLevel());
    }

    public function test_it_has_private_constructor(): void
    {
        $reflection = new \ReflectionClass(AuthorityChain::class);
        $constructor = $reflection->getConstructor();

        $this->assertTrue($constructor->isPrivate(),
            'AuthorityChain must have private constructor - use capture() factory'
        );
    }

    public function test_it_is_immutable_readonly(): void
    {
        $chain = AuthorityChain::capture(
            actorId: MemberId::from('user-123'),
            role: GovernanceRole::COUNTRY_PRESIDENT,
            delegationPath: AuthorityPath::fromArray(['ICC', 'CONTINENT']),
            capturedAt: new DateTimeImmutable()
        );

        $reflection = new \ReflectionClass($chain);

        foreach ($reflection->getProperties() as $property) {
            $this->assertTrue(
                $property->isReadOnly(),
                sprintf('Property %s must be readonly', $property->getName())
            );
        }
    }

    public function test_value_equality_based_on_all_fields(): void
    {
        $time = new DateTimeImmutable('2026-01-01 10:00:00');

        $a = AuthorityChain::capture(
            actorId: MemberId::from('user-123'),
            role: GovernanceRole::COUNTRY_PRESIDENT,
            delegationPath: AuthorityPath::fromArray(['ICC', 'CONTINENT', 'COUNTRY']),
            capturedAt: $time
        );

        $b = AuthorityChain::capture(
            actorId: MemberId::from('user-123'),
            role: GovernanceRole::COUNTRY_PRESIDENT,
            delegationPath: AuthorityPath::fromArray(['ICC', 'CONTINENT', 'COUNTRY']),
            capturedAt: $time
        );

        $this->assertTrue($a->equals($b));
    }

    public function test_it_detects_unequal_authority_chains(): void
    {
        $time = new DateTimeImmutable('2026-01-01 10:00:00');

        $a = AuthorityChain::capture(
            actorId: MemberId::from('user-123'),
            role: GovernanceRole::COUNTRY_PRESIDENT,
            delegationPath: AuthorityPath::fromArray(['ICC', 'CONTINENT', 'COUNTRY']),
            capturedAt: $time
        );

        $b = AuthorityChain::capture(
            actorId: MemberId::from('user-456'),
            role: GovernanceRole::COUNTRY_PRESIDENT,
            delegationPath: AuthorityPath::fromArray(['ICC', 'CONTINENT', 'COUNTRY']),
            capturedAt: $time
        );

        $this->assertFalse($a->equals($b));
    }

    public function test_it_prevents_empty_delegation_path(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Delegation path cannot be empty');

        AuthorityPath::fromArray([]);
    }

    public function test_authority_level_cannot_be_passed_as_parameter(): void
    {
        $reflection = new \ReflectionClass(AuthorityChain::class);
        $captureMethod = $reflection->getMethod('capture');
        $paramNames = array_map(
            fn($p) => $p->getName(),
            $captureMethod->getParameters()
        );

        $this->assertNotContains('authorityLevel', $paramNames,
            'Authority level must be derived from path, not passed as parameter'
        );
    }

    public function test_all_properties_are_typed_value_objects(): void
    {
        $chain = AuthorityChain::capture(
            actorId: MemberId::from('user-123'),
            role: GovernanceRole::COUNTRY_PRESIDENT,
            delegationPath: AuthorityPath::fromArray(['ICC', 'CONTINENT']),
            capturedAt: new DateTimeImmutable()
        );

        $reflection = new \ReflectionClass($chain);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $type = $property->getType();
            $typeName = $type->getName();

            $isValueObject = in_array($typeName, [
                MemberId::class,
                GovernanceRole::class,
                AuthorityPath::class,
                DateTimeImmutable::class,
                'int', // authorityLevel is derived as int
            ], true);

            $this->assertTrue($isValueObject || $type->isBuiltin(),
                sprintf('Property %s type %s is not a value object', $property->getName(), $typeName)
            );
        }
    }

    public function test_captured_timestamp_is_immutable(): void
    {
        $originalTime = new DateTimeImmutable('2026-01-01 10:00:00');

        $chain = AuthorityChain::capture(
            actorId: MemberId::from('user-123'),
            role: GovernanceRole::COUNTRY_PRESIDENT,
            delegationPath: AuthorityPath::fromArray(['ICC', 'CONTINENT']),
            capturedAt: $originalTime
        );

        $modifiedTime = $originalTime->modify('+1 day');

        $this->assertNotEquals($modifiedTime, $chain->capturedAt());
        $this->assertSame($originalTime, $chain->capturedAt());
    }
}
