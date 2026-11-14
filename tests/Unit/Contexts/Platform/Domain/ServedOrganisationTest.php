<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Platform\Domain;

use App\Contexts\Platform\Domain\CounterpartyVerification;
use App\Contexts\Platform\Domain\FoundingApplication;
use App\Contexts\Platform\Domain\DatabaseLocator;
use App\Contexts\Platform\Domain\LifecycleRefusal;
use App\Contexts\Platform\Domain\OperationalReadiness;
use App\Contexts\Platform\Domain\PlatformStatus;
use App\Contexts\Platform\Domain\SchemaState;
use App\Contexts\Platform\Domain\ServedOrganisation;
use App\Contexts\Platform\Domain\ServedOrganisationRefused;
use App\Contexts\Platform\Domain\ServiceOperationGrant;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-345 task 1: the ServedOrganisation aggregate, the Platform registry entry of one organisation.
 * Lifecycle (release 1): pending → provisioning → operational (D-185; D-193 F-02, F-07). `suspended` and `closed`
 * are a later release (D-193 §3.2). Schema state is separate from status and blocks routing (D-178 §13.6).
 * Health is never modelled here (D-193 §3.2: an observation, never a status).
 */
final class ServedOrganisationTest extends TestCase
{
    private const ID = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';

    /** task 5a: every registry entry carries its founding application */
    private static function application(): FoundingApplication
    {
        return new FoundingApplication('founding-evidence:test', '7e6d5c4b-3a29-4180-9f8e-7d6c5b4a3921', ['1a2b3c4d-5e6f-4a7b-8c9d-0e1f2a3b4c5d']);
    }

    private static function grant(): ServiceOperationGrant
    {
        return new ServiceOperationGrant('terms-2026-11', new \DateTimeImmutable('2026-11-12T10:00:00Z'), 'account:applicant-1', [str_repeat('a', 64)]);
    }

    private static function locator(): DatabaseLocator
    {
        return new DatabaseLocator('cluster-1', 'org_0b9e7a52', 'secret-store:org_0b9e7a52');
    }

    private static function ready(): OperationalReadiness
    {
        return new OperationalReadiness(true, true, true, 1);
    }

    private static function provisioning(): ServedOrganisation
    {
        $o = ServedOrganisation::register(TenantId::fromString(self::ID), self::application());
        $o->recordCounterpartyVerified(new CounterpartyVerification('agreement:1'));
        $o->recordServiceOperationGrant(self::grant());
        $o->startProvisioning();

        return $o;
    }

    private static function operational(): ServedOrganisation
    {
        $o = self::provisioning();
        $o->assignLocator(self::locator());
        $o->markSchemaCurrent();
        $o->markOperational(self::ready());

        return $o;
    }

    private function assertRefused(LifecycleRefusal $expected, callable $act): void
    {
        try {
            $act();
            $this->fail("expected refusal {$expected->name}");
        } catch (ServedOrganisationRefused $e) {
            $this->assertSame($expected, $e->refusal);
        }
    }

    public function test_a_registered_organisation_is_pending_and_not_routable(): void
    {
        $o = ServedOrganisation::register(TenantId::fromString(self::ID), self::application());

        $this->assertSame(self::ID, $o->id()->value());
        $this->assertSame(PlatformStatus::Pending, $o->status());
        $this->assertNull($o->schemaState(), 'no migration has run: no schema state recorded yet');
        $this->assertNull($o->locator());
        $this->assertNull($o->serviceOperationGrant());
        $this->assertFalse($o->isRoutable(), 'a registry entry alone never makes an organisation routable');
    }

    public function test_provisioning_needs_both_a_verified_counterparty_and_a_recorded_grant(): void
    {
        $neither = ServedOrganisation::register(TenantId::fromString(self::ID), self::application());
        $this->assertRefused(LifecycleRefusal::CounterpartyNotVerified, fn () => $neither->startProvisioning());

        $onlyGrant = ServedOrganisation::register(TenantId::fromString(self::ID), self::application());
        $onlyGrant->recordServiceOperationGrant(self::grant());
        $this->assertRefused(LifecycleRefusal::CounterpartyNotVerified, fn () => $onlyGrant->startProvisioning());

        $onlyCounterparty = ServedOrganisation::register(TenantId::fromString(self::ID), self::application());
        $onlyCounterparty->recordCounterpartyVerified(new CounterpartyVerification('agreement:1'));
        $this->assertRefused(LifecycleRefusal::NoServiceOperationGrant, fn () => $onlyCounterparty->startProvisioning());

        $this->assertSame(PlatformStatus::Pending, $neither->status());
        $this->assertSame(PlatformStatus::Provisioning, self::provisioning()->status());
    }

    public function test_no_technical_operation_while_pending(): void
    {
        $o = ServedOrganisation::register(TenantId::fromString(self::ID), self::application());

        $this->assertRefused(LifecycleRefusal::NoTechnicalOperationWhilePending, fn () => $o->assignLocator(self::locator()));
        $this->assertRefused(LifecycleRefusal::NoTechnicalOperationWhilePending, fn () => $o->markSchemaCurrent());
        $this->assertRefused(LifecycleRefusal::NoTechnicalOperationWhilePending, fn () => $o->markMigrationFailed());
        $this->assertNull($o->locator());
        $this->assertNull($o->schemaState());
    }

    public function test_the_grant_and_the_counterparty_verification_are_recorded_once_and_only_before_provisioning(): void
    {
        $o = ServedOrganisation::register(TenantId::fromString(self::ID), self::application());
        $o->recordCounterpartyVerified(new CounterpartyVerification('agreement:1'));
        $o->recordServiceOperationGrant(self::grant());

        $this->assertRefused(LifecycleRefusal::GrantAlreadyRecorded, fn () => $o->recordServiceOperationGrant(self::grant()));
        $this->assertRefused(LifecycleRefusal::CounterpartyAlreadyVerified, fn () => $o->recordCounterpartyVerified(new CounterpartyVerification('agreement:2')));
        $this->assertSame('account:applicant-1', $o->serviceOperationGrant()?->acceptedBy);
    }

    public function test_the_locator_is_assigned_once_during_provisioning(): void
    {
        $o = self::provisioning();
        $o->assignLocator(self::locator());

        $this->assertTrue(self::locator()->equals($o->locator()));
        $this->assertRefused(LifecycleRefusal::LocatorAlreadyAssigned, fn () => $o->assignLocator(new DatabaseLocator('cluster-2', 'org_other', 'ref:other')));
        $this->assertRefused(LifecycleRefusal::NotProvisioning, fn () => self::operational()->assignLocator(self::locator()));
    }

    public function test_operational_needs_a_locator_a_current_schema_and_complete_readiness(): void
    {
        $noLocator = self::provisioning();
        $noLocator->markSchemaCurrent();
        $this->assertRefused(LifecycleRefusal::NoLocator, fn () => $noLocator->markOperational(self::ready()));

        $noSchema = self::provisioning();
        $noSchema->assignLocator(self::locator());
        $this->assertRefused(LifecycleRefusal::SchemaNotCurrent, fn () => $noSchema->markOperational(self::ready()));

        $failed = self::provisioning();
        $failed->assignLocator(self::locator());
        $failed->markMigrationFailed();
        $this->assertRefused(LifecycleRefusal::SchemaNotCurrent, fn () => $failed->markOperational(self::ready()));

        foreach ([new OperationalReadiness(false, true, true, 1), new OperationalReadiness(true, false, true, 1), new OperationalReadiness(true, true, false, 1), new OperationalReadiness(true, true, true, 0)] as $incomplete) {
            $o = self::provisioning();
            $o->assignLocator(self::locator());
            $o->markSchemaCurrent();
            $this->assertRefused(LifecycleRefusal::NotReady, fn () => $o->markOperational($incomplete));
            $this->assertSame(PlatformStatus::Provisioning, $o->status());
        }
    }

    public function test_operational_is_reached_only_from_provisioning(): void
    {
        $pending = ServedOrganisation::register(TenantId::fromString(self::ID), self::application());
        $this->assertRefused(LifecycleRefusal::NotProvisioning, fn () => $pending->markOperational(self::ready()));
        $this->assertRefused(LifecycleRefusal::NotProvisioning, fn () => self::operational()->markOperational(self::ready()));
        $this->assertRefused(LifecycleRefusal::NotPending, fn () => self::provisioning()->startProvisioning());
    }

    public function test_an_operational_organisation_with_a_current_schema_is_routable(): void
    {
        $o = self::operational();

        $this->assertSame(PlatformStatus::Operational, $o->status());
        $this->assertSame(SchemaState::Current, $o->schemaState());
        $this->assertTrue($o->isRoutable());
        $this->assertFalse(self::provisioning()->isRoutable(), 'provisioning is never routable');
    }

    public function test_a_failed_migration_blocks_routing_without_changing_status_and_a_repair_restores_it(): void
    {
        $o = self::operational();

        $o->markMigrationFailed();
        $this->assertSame(PlatformStatus::Operational, $o->status(), 'schema state is not a status');
        $this->assertSame(SchemaState::MigrationFailed, $o->schemaState());
        $this->assertFalse($o->isRoutable());

        $o->markSchemaCurrent();
        $this->assertTrue($o->isRoutable());
    }
}
