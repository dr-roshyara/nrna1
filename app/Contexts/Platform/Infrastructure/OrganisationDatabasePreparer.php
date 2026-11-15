<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure;

use App\Contexts\Platform\Application\Ports\ServedOrganisationRepository;
use App\Contexts\Platform\Application\ResolvedOrganisation;
use App\Contexts\Platform\Domain\PlatformStatus;
use App\Contexts\Platform\Domain\SchemaState;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Founding step 3 inside the dedicated provisioning process (PBDIGIT-345 task 5b; tactical design §3.6, M-5, F-13):
 * - the locator comes from the REGISTRY (never from the caller); the organisation must be `provisioning`;
 * - the organisation migration set is applied to the organisation's OWN database (task 4);
 * - the database is bound to the organisation: its one `organisation_identity` row is the registry id. A database
 *   already bound to another organisation is refused. Membership's own trigger then creates its ledger clock row.
 * Idempotent. It does not write the registry: the founding use case records the outcome.
 */
final class OrganisationDatabasePreparer
{
    public function __construct(
        private readonly ServedOrganisationRepository $registry,
        private readonly OrganisationConnectionFactory $factory,
        private readonly OrganisationMigrations $migrations,
    ) {
    }

    public function prepare(TenantId $id): void
    {
        DedicatedProvisioningProcess::requireFor($id);
        $o = $this->registry->find($id) ?? throw new OrganisationProvisioningRefused("Organisation {$id->value()} is not in the registry.");
        if ($o->status() !== PlatformStatus::Provisioning || $o->locator() === null) {
            throw new OrganisationProvisioningRefused("Organisation {$id->value()} is not provisioning with a database locator.");
        }
        $context = $this->factory->establish(new ResolvedOrganisation($id, $o->locator()));
        try {
            $this->migrations->apply($context);
            $db = $context->connection;
            $db->insert('INSERT INTO organisation_identity (id) VALUES (?::uuid) ON CONFLICT ON CONSTRAINT organisation_identity_one_row DO NOTHING', [$id->value()]);
            $bound = $db->selectOne('SELECT id::text AS id FROM organisation_identity');
            if ($bound?->id !== $id->value()) {
                throw new OrganisationProvisioningRefused("The database at organisation {$id->value()}'s locator is bound to another organisation.");
            }
        } finally {
            $this->factory->release($context);
        }
    }

    /**
     * Task 11 (E-4, §3.8 migration repair): re-apply the organisation migration set to an organisation whose schema is
     * migration-failed, in this dedicated process; then verify (never write) its identity binding. Reads only
     * `migrations` and `organisation_identity`: technical metadata, never a business table (T-16).
     */
    public function repair(TenantId $id): void
    {
        DedicatedProvisioningProcess::requireFor($id);
        $o = $this->registry->find($id) ?? throw new OrganisationProvisioningRefused("Organisation {$id->value()} is not in the registry.");
        if ($o->locator() === null || $o->schemaState() !== SchemaState::MigrationFailed) {
            throw new OrganisationProvisioningRefused("Organisation {$id->value()} has no failed schema to repair.");
        }
        $context = $this->factory->establish(new ResolvedOrganisation($id, $o->locator()));
        try {
            $this->migrations->apply($context);
            if ($context->connection->selectOne('SELECT id::text AS id FROM organisation_identity')?->id !== $id->value()) {
                throw new OrganisationProvisioningRefused("The database at organisation {$id->value()}'s locator is bound to another organisation.");
            }
        } finally {
            $this->factory->release($context);
        }
    }
}
