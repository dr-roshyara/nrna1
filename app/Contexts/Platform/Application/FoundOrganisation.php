<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application;

use App\Contexts\Platform\Application\Ports\LegacyOrganisationRegister;
use App\Contexts\Platform\Application\Ports\ContactAccounts;
use App\Contexts\Platform\Application\Ports\OrganisationProvisioner;
use App\Contexts\Platform\Application\Ports\OrganisationReadinessProbe;
use App\Contexts\Platform\Domain\OperationalReadiness;
use App\Contexts\Platform\Application\Ports\ServedOrganisationRepository;
use App\Contexts\Platform\Domain\CounterpartyVerification;
use App\Contexts\Platform\Domain\DatabaseLocator;
use App\Contexts\Platform\Domain\SchemaState;
use App\Contexts\Platform\Domain\FoundingApplication;
use App\Contexts\Platform\Domain\PlatformStatus;
use App\Contexts\Platform\Domain\ServedOrganisation;
use App\Contexts\Platform\Domain\ServiceOperationGrant;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Founding an organisation (F-1, tactical design §3.6), central steps 1 and 2 (task 5a). No new authority model: each
 * step records evidence or a recorded act; it grants no membership, workspace access or authority.
 *
 * Every step is IDEMPOTENT and is resumed by repeating it (T-9): the registry entry is the progress record. Repeating
 * a step with the same input is a no-op; with different input it is refused (the first record stands). So a crash at
 * any point (a lost save) leaves either the previous state or the new one, and repeating the step completes it.
 */
final class FoundOrganisation
{
    public function __construct(
        private readonly ServedOrganisationRepository $registry,
        private readonly LegacyOrganisationRegister $legacy,
        private readonly OrganisationProvisioner $provisioner,
        private readonly OrganisationReadinessProbe $probe,
        private readonly ContactAccounts $contacts,
    ) {
    }

    /** Step 1: a `pending` registry entry with its founding application. L-2: a legacy organisation is refused. */
    public function register(TenantId $id, FoundingApplication $application): void
    {
        if ($this->legacy->contains($id)) {
            throw new FoundingRefused("Organisation {$id->value()} is a legacy organisation: it cannot enter the registry in release 1 (L-2).");
        }
        $existing = $this->registry->find($id);
        if ($existing !== null) {
            if (!$existing->foundingApplication()->equals($application)) {
                throw new FoundingRefused("Organisation {$id->value()} is already registered with another founding application.");
            }

            return;   // resumed: already registered with this application
        }
        $this->registry->add(ServedOrganisation::register($id, $application));
    }

    /** Step 2a: the counterparty verified (D-175). */
    public function verifyCounterparty(TenantId $id, CounterpartyVerification $verification): void
    {
        $o = $this->entry($id);
        if ($o->counterpartyVerification() == $verification) {
            return;
        }
        $o->recordCounterpartyVerified($verification);   // refuses a second, different verification
        $this->registry->save($o);
    }

    /** Step 2b: the service-operation grant recorded (D-166); immutable once recorded. */
    public function recordServiceOperationGrant(TenantId $id, ServiceOperationGrant $grant): void
    {
        $o = $this->entry($id);
        if ($o->serviceOperationGrant() == $grant) {
            return;
        }
        $o->recordServiceOperationGrant($grant);   // refuses a second, different grant
        $this->registry->save($o);
    }

    /** Step 2c: `provisioning`, only after the verification and the grant (D-185). */
    public function startProvisioning(TenantId $id): void
    {
        $o = $this->entry($id);
        if ($o->status() !== PlatformStatus::Pending) {
            return;   // resumed: already provisioning or beyond
        }
        $o->startProvisioning();
        $this->registry->save($o);
    }

    /**
     * Step 3 (task 5b): bind the organisation's database locator (recorded BEFORE anything is prepared), prepare the
     * database through the provisioner (its own process), then record the schema as current. A failed preparation is
     * recorded as a failed migration and reported; repeating the step resumes it. Never makes it operational.
     */
    public function provision(TenantId $id, DatabaseLocator $locator): void
    {
        $o = $this->entry($id);
        if ($o->locator() === null) {
            $o->assignLocator($locator);   // refuses unless provisioning
            $this->registry->save($o);
        } elseif (!$o->locator()->equals($locator)) {
            throw new FoundingRefused("Organisation {$id->value()} is already bound to another database.");
        }
        if ($o->status() !== PlatformStatus::Provisioning) {
            return;   // resumed after step 5: nothing to prepare here
        }
        try {
            $this->provisioner->prepare($id);
        } catch (ProvisioningFailed $e) {
            $o->markMigrationFailed();
            $this->registry->save($o);
            throw $e;
        }
        if ($o->schemaState() !== SchemaState::Current) {
            $o->markSchemaCurrent();
            $this->registry->save($o);
        }
    }

    /**
     * Step 5 (task 5c; §3.7 L-1, D-193 F-07): `operational` only if, at this moment, the organisation database shows
     * the ledger schema, the identity row equal to the registry id and the ledger clock row, AND at least one of the
     * founding application's platform contacts has an active account. Each missing fact is named in the refusal
     * (T-13). The aggregate re-checks everything (provisioning, locator, schema current, readiness).
     */
    public function markOperational(TenantId $id): void
    {
        $o = $this->entry($id);
        if ($o->status() === PlatformStatus::Operational) {
            return;   // resumed: nothing is inspected again
        }
        if ($o->status() !== PlatformStatus::Provisioning || $o->locator() === null || $o->schemaState() !== SchemaState::Current) {
            // an earlier step is missing: the aggregate names which (NotProvisioning, NoLocator, SchemaNotCurrent),
            // before any database is inspected
            $o->markOperational(new OperationalReadiness(false, false, false, 0));
        }
        $facts = $this->probe->inspect($id);
        $readiness = new OperationalReadiness($facts->ledgerSchemaPresent, $facts->identityRowMatches, $facts->ledgerClockRowPresent,
            $this->contacts->activeCount($o->foundingApplication()->initialContactAccounts));
        if ($readiness->missing() !== []) {
            throw new FoundingRefused("Organisation {$id->value()} is not ready to be operational; missing: " . implode(', ', $readiness->missing()) . '.');
        }
        $o->markOperational($readiness);
        $this->registry->save($o);
    }

    private function entry(TenantId $id): ServedOrganisation
    {
        return $this->registry->find($id) ?? throw new FoundingRefused("Organisation {$id->value()} is not in the registry.");
    }
}
