<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure;

use App\Contexts\Platform\Application\Ports\OrganisationDatabaseFacts;
use App\Contexts\Platform\Application\Ports\OrganisationReadinessProbe;
use App\Contexts\Platform\Application\Ports\ServedOrganisationRepository;
use App\Contexts\Platform\Application\ResolvedOrganisation;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\Connection;

/**
 * PBDIGIT-345 task 5c: the L-1 facts (tactical design §3.7), READ in the organisation's own database at the locator
 * the registry holds. It never migrates (no migrator: it compares the `migrations` history with the organisation
 * set's files on disk) and releases the connection afterwards. The ledger facts are MEMBERSHIP's answer, through its
 * function org_membership_ledger_readiness (organisation migration 300300): Platform never names or reads a ledger
 * table (R5, MembershipLedgerIsolationTest) and imports no Membership code.
 */
final class PostgresOrganisationReadinessProbe implements OrganisationReadinessProbe
{
    /** @param list<string> $migrationPaths the organisation migration set (organisation_routing.migration_paths) */
    public function __construct(
        private readonly ServedOrganisationRepository $registry,
        private readonly OrganisationConnectionFactory $factory,
        private readonly array $migrationPaths,
    ) {
    }

    public function inspect(TenantId $id): OrganisationDatabaseFacts
    {
        $locator = $this->registry->find($id)?->locator()
            ?? throw new OrganisationProvisioningRefused("Organisation {$id->value()} has no database locator to inspect.");
        $context = $this->factory->establish(new ResolvedOrganisation($id, $locator));
        try {
            return $this->factsOn($context->connection, $id);
        } finally {
            $this->factory->release($context);
        }
    }

    /**
     * The same L-1 facts read on ANY connection to an organisation database, e.g. a restored scratch copy
     * (task 12b, T-17). Reads technical metadata only (migrations, organisation_identity, Membership's readiness answer).
     */
    public function factsOn(Connection $db, TenantId $id): OrganisationDatabaseFacts
    {
        $recorded = self::has($db, 'migrations') ? array_map(static fn ($r): string => $r->migration, $db->select('SELECT migration FROM migrations')) : [];
        $ledger = (bool) $db->selectOne("SELECT to_regprocedure('org_membership_ledger_readiness(uuid)') IS NOT NULL AS f")->f
            ? $db->selectOne('SELECT schema_present, clock_row_present FROM org_membership_ledger_readiness(?::uuid)', [$id->value()])
            : null;   // Membership's readiness function is itself part of the set: absent means not current
        $identity = self::has($db, 'organisation_identity')
            ? array_map(static fn ($r): string => $r->id, $db->select('SELECT id::text AS id FROM organisation_identity')) : [];

        return new OrganisationDatabaseFacts(
            array_diff($this->expectedMigrations(), $recorded) === [] && (bool) $ledger?->schema_present,
            $identity === [$id->value()],
            (bool) $ledger?->clock_row_present,
        );
    }

    /** @return list<string> */
    private function expectedMigrations(): array
    {
        $names = [];
        foreach ($this->migrationPaths as $path) {
            foreach (glob(rtrim($path, '/') . '/*_*.php') ?: [] as $file) {
                $names[] = basename($file, '.php');
            }
        }
        if ($names === []) {
            throw new OrganisationProvisioningRefused('The organisation migration set is empty: readiness cannot be judged.');
        }

        return $names;
    }

    private static function has(Connection $db, string $table): bool
    {
        return (bool) $db->selectOne('SELECT to_regclass(?) IS NOT NULL AS t', ["public.{$table}"])->t;
    }
}
