<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Platform\Domain;

use App\Contexts\Platform\Domain\CounterpartyVerification;
use App\Contexts\Platform\Domain\DatabaseLocator;
use App\Contexts\Platform\Domain\OperationalReadiness;
use App\Contexts\Platform\Domain\PlatformStatus;
use App\Contexts\Platform\Domain\SchemaState;
use App\Contexts\Platform\Domain\ServedOrganisation;
use App\Contexts\Platform\Domain\ServedOrganisationSnapshot;
use App\Contexts\Platform\Domain\ServiceOperationGrant;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-345 task 2: the registry entry is rebuilt from storage ONLY through the aggregate's own path
 * (Hexagonal DDD baseline §5: never by setting private state from rows). fromSnapshot re-checks the invariants of
 * ServedOrganisation, so a stored state the domain could never have produced is refused, not routed.
 */
final class ServedOrganisationSnapshotTest extends TestCase
{
    private const ID = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';

    private static function grant(): ServiceOperationGrant
    {
        return new ServiceOperationGrant('terms-2026-11', new \DateTimeImmutable('2026-11-12T10:00:00Z'), 'account:applicant-1', [str_repeat('a', 64)]);
    }

    private static function locator(): DatabaseLocator
    {
        return new DatabaseLocator('cluster-1', 'org_0b9e7a52', 'secret-store:org_0b9e7a52');
    }

    private static function operational(): ServedOrganisation
    {
        $o = ServedOrganisation::register(TenantId::fromString(self::ID));
        $o->recordCounterpartyVerified(new CounterpartyVerification('agreement:1'));
        $o->recordServiceOperationGrant(self::grant());
        $o->startProvisioning();
        $o->assignLocator(self::locator());
        $o->markSchemaCurrent();
        $o->markOperational(new OperationalReadiness(true, true, true, 1));

        return $o;
    }

    public function test_every_reachable_state_survives_a_round_trip(): void
    {
        $pending = ServedOrganisation::register(TenantId::fromString(self::ID));
        $failed = self::operational();
        $failed->markMigrationFailed();

        foreach ([$pending, self::operational(), $failed] as $original) {
            $rebuilt = ServedOrganisation::fromSnapshot($original->snapshot());
            $this->assertEquals($original->snapshot(), $rebuilt->snapshot());
            $this->assertSame($original->isRoutable(), $rebuilt->isRoutable());
        }
        $this->assertTrue(ServedOrganisation::fromSnapshot(self::operational()->snapshot())->isRoutable());
        $this->assertFalse(ServedOrganisation::fromSnapshot($failed->snapshot())->isRoutable(), 'a failed migration blocks routing after a reload too');
    }

    /** @return array<string, array{\Closure(ServedOrganisationSnapshot): ServedOrganisationSnapshot}> */
    public static function impossibleStates(): array
    {
        $with = static fn (array $changes) => static fn (ServedOrganisationSnapshot $s): ServedOrganisationSnapshot => new ServedOrganisationSnapshot(...array_merge(get_object_vars($s), $changes));

        return [
            'operational without a locator' => [$with(['locator' => null])],
            'operational without a schema state' => [$with(['schemaState' => null])],
            'operational without a grant' => [$with(['grant' => null])],
            'operational without a verified counterparty' => [$with(['counterparty' => null])],
            'pending with a locator' => [$with(['status' => PlatformStatus::Pending])],
            'provisioning without a grant' => [$with(['status' => PlatformStatus::Provisioning, 'grant' => null])],
        ];
    }

    #[DataProvider('impossibleStates')]
    public function test_a_stored_state_the_domain_could_never_produce_is_refused(\Closure $corrupt): void
    {
        $this->expectException(\UnexpectedValueException::class);

        ServedOrganisation::fromSnapshot($corrupt(self::operational()->snapshot()));
    }

    public function test_the_snapshot_carries_no_password(): void
    {
        $this->assertSame('secret-store:org_0b9e7a52', self::operational()->snapshot()->locator?->credentialRef, 'only a reference to the credential');
        $this->assertSame(SchemaState::Current, self::operational()->snapshot()->schemaState);
    }
}
