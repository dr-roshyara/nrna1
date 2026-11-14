<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Domain;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Aggregate: the Platform registry entry of ONE organisation (D-193 Part 4; D-173). It decides only whether
 * PublicDigit may serve the organisation technically. It owns no members, units, authority or content, and its
 * transitions invent no authority (D-193 §3.2).
 *
 * Invariants (release 1):
 * - lifecycle pending → provisioning → operational (D-185; D-193 F-02, F-07); never backwards in release 1;
 * - pending → provisioning only with a verified counterparty (D-175) AND a recorded service-operation grant (D-166);
 * - no technical operation while pending: no locator, no schema state (D-185);
 * - the grant is recorded once and is immutable (D-193 §3.3); the locator is assigned once, by provisioning (ISO-2);
 * - provisioning → operational only with a locator, a current schema and complete readiness (L-1, D-219 (1); F-07);
 * - routable ⇔ operational AND schema current. A failed migration blocks routing and changes no status
 *   (D-178 §13.6). Health is not modelled: it is an observation, never a status (D-193 §3.2).
 */
final class ServedOrganisation
{
    private PlatformStatus $status = PlatformStatus::Pending;
    private ?SchemaState $schemaState = null;
    private ?DatabaseLocator $locator = null;
    private ?ServiceOperationGrant $grant = null;
    private ?CounterpartyVerification $counterparty = null;

    private function __construct(private readonly TenantId $id, private readonly FoundingApplication $application)
    {
    }

    /** Founding step 1 (§3.6): `pending`, with the founding application. No technical operation yet (D-185). */
    public static function register(TenantId $id, FoundingApplication $application): self
    {
        return new self($id, $application);
    }

    public function recordCounterpartyVerified(CounterpartyVerification $verification): void
    {
        $this->requireStatus(PlatformStatus::Pending, LifecycleRefusal::NotPending);
        if ($this->counterparty !== null) {
            throw new ServedOrganisationRefused(LifecycleRefusal::CounterpartyAlreadyVerified);
        }
        $this->counterparty = $verification;
    }

    public function recordServiceOperationGrant(ServiceOperationGrant $grant): void
    {
        $this->requireStatus(PlatformStatus::Pending, LifecycleRefusal::NotPending);
        if ($this->grant !== null) {
            throw new ServedOrganisationRefused(LifecycleRefusal::GrantAlreadyRecorded);
        }
        $this->grant = $grant;
    }

    public function startProvisioning(): void
    {
        $this->requireStatus(PlatformStatus::Pending, LifecycleRefusal::NotPending);
        if ($this->counterparty === null) {
            throw new ServedOrganisationRefused(LifecycleRefusal::CounterpartyNotVerified);
        }
        if ($this->grant === null) {
            throw new ServedOrganisationRefused(LifecycleRefusal::NoServiceOperationGrant);
        }
        $this->status = PlatformStatus::Provisioning;
    }

    public function assignLocator(DatabaseLocator $locator): void
    {
        $this->refuseWhilePending();
        $this->requireStatus(PlatformStatus::Provisioning, LifecycleRefusal::NotProvisioning);
        if ($this->locator !== null) {
            throw new ServedOrganisationRefused(LifecycleRefusal::LocatorAlreadyAssigned);
        }
        $this->locator = $locator;
    }

    public function markSchemaCurrent(): void
    {
        $this->refuseWhilePending();
        $this->schemaState = SchemaState::Current;
    }

    public function markMigrationFailed(): void
    {
        $this->refuseWhilePending();
        $this->schemaState = SchemaState::MigrationFailed;
    }

    public function markOperational(OperationalReadiness $readiness): void
    {
        $this->requireStatus(PlatformStatus::Provisioning, LifecycleRefusal::NotProvisioning);
        if ($this->locator === null) {
            throw new ServedOrganisationRefused(LifecycleRefusal::NoLocator);
        }
        if ($this->schemaState !== SchemaState::Current) {
            throw new ServedOrganisationRefused(LifecycleRefusal::SchemaNotCurrent);
        }
        if (!$readiness->isComplete()) {
            throw new ServedOrganisationRefused(LifecycleRefusal::NotReady);
        }
        $this->status = PlatformStatus::Operational;
    }

    public function snapshot(): ServedOrganisationSnapshot
    {
        return new ServedOrganisationSnapshot($this->id, $this->status, $this->schemaState, $this->locator, $this->grant, $this->counterparty, $this->application);
    }

    /**
     * The only way back from storage (task 2; baseline §5). Re-checks the invariants above, so a stored state the
     * lifecycle could never have produced is refused here and never routed.
     *
     * @throws \UnexpectedValueException for an impossible stored state
     */
    public static function fromSnapshot(ServedOrganisationSnapshot $s): self
    {
        $refuse = static fn (string $why) => throw new \UnexpectedValueException("Stored registry entry {$s->id->value()} is impossible: {$why}.");
        if ($s->status === PlatformStatus::Pending && ($s->locator !== null || $s->schemaState !== null)) {
            $refuse('no technical operation while pending (D-185)');
        }
        if ($s->status !== PlatformStatus::Pending && ($s->counterparty === null || $s->grant === null)) {
            $refuse('provisioning needs a verified counterparty and a grant (D-175, D-166)');
        }
        if ($s->status === PlatformStatus::Operational && ($s->locator === null || $s->schemaState === null)) {
            $refuse('operational needs a locator and a schema state (L-1)');
        }
        if ($s->application === null) {
            $refuse('a registry entry records its founding application (§3.6 step 1)');
        }
        $o = new self($s->id, $s->application);
        $o->status = $s->status;
        $o->schemaState = $s->schemaState;
        $o->locator = $s->locator;
        $o->grant = $s->grant;
        $o->counterparty = $s->counterparty;

        return $o;
    }

    public function isRoutable(): bool
    {
        return $this->status === PlatformStatus::Operational && $this->schemaState === SchemaState::Current;
    }

    public function id(): TenantId
    {
        return $this->id;
    }

    public function status(): PlatformStatus
    {
        return $this->status;
    }

    public function schemaState(): ?SchemaState
    {
        return $this->schemaState;
    }

    public function locator(): ?DatabaseLocator
    {
        return $this->locator;
    }

    public function serviceOperationGrant(): ?ServiceOperationGrant
    {
        return $this->grant;
    }

    public function counterpartyVerification(): ?CounterpartyVerification
    {
        return $this->counterparty;
    }

    public function foundingApplication(): FoundingApplication
    {
        return $this->application;
    }

    private function requireStatus(PlatformStatus $required, LifecycleRefusal $otherwise): void
    {
        if ($this->status !== $required) {
            throw new ServedOrganisationRefused($otherwise);
        }
    }

    private function refuseWhilePending(): void
    {
        if ($this->status === PlatformStatus::Pending) {
            throw new ServedOrganisationRefused(LifecycleRefusal::NoTechnicalOperationWhilePending);
        }
    }
}
