<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\Facades\DB;

/**
 * The ORGANISATION migration set (PBDIGIT-345 task 4; tactical design §3.5, M-5): database/migrations/organisation/,
 * applied to ONE organisation's own database through its OrganisationContext. Provisioning (task 5, F-1 step 3) calls
 * apply(); nothing else should. The central `migrate` never sees this set (the migrator does not recurse).
 *
 * - Runs only on the connection the factory established FOR THAT ORGANISATION (its derived name), never on the
 *   default connection or on the default connection's database: otherwise OrganisationMigrationRefused, nothing run.
 * - The migration history (`migrations`) lives in the organisation's own database.
 * - Each migration runs in its own transaction (PostgreSQL DDL is transactional): a failing one leaves nothing
 *   behind and stays pending; those before it stay applied and recorded. A repeat applies only what is pending.
 * - The migrator's default connection is switched only for the run and restored afterwards (Migrator::usingConnection).
 *   KNOWN LIMIT: Laravel migrations use the Schema:: and DB:: facades, so the default must point at the organisation
 *   database while they run. The switch is scoped and restored in a finally block, but it is process-wide while it
 *   lasts: provisioning must run this in its own process (task 5), never inside a request or a worker serving others.
 */
final class OrganisationMigrations
{
    /** @param list<string> $paths the organisation set (config organisation_routing.migration_paths), one per owning context */
    public function __construct(private readonly Migrator $migrator, private readonly array $paths)
    {
    }

    /** @return list<string> the migrations applied now (names, in order) */
    public function apply(OrganisationContext $context): array
    {
        return $this->migrator->usingConnection($this->guard($context), function (): array {
            if (!$this->migrator->repositoryExists()) {
                $this->migrator->getRepository()->createRepository();
            }

            return array_values(array_map(fn (string $file): string => $this->migrator->getMigrationName($file), $this->migrator->run($this->paths)));
        });
    }

    /** @return list<string> the migrations of the set not yet applied to this organisation's database */
    public function pending(OrganisationContext $context): array
    {
        return $this->migrator->usingConnection($this->guard($context), function (): array {
            $all = array_keys($this->migrator->getMigrationFiles($this->paths));
            $ran = $this->migrator->repositoryExists() ? $this->migrator->getRepository()->getRan() : [];

            return array_values(array_diff($all, $ran));
        });
    }

    private function guard(OrganisationContext $context): string
    {
        $name = $context->connection->getName();
        $default = DB::getDefaultConnection();
        if ($name === $default || $name !== OrganisationConnectionFactory::connectionName($context->organisationId)) {
            throw new OrganisationMigrationRefused("The organisation migration set runs only on the organisation's own connection, not on '{$name}'.");
        }
        if ($context->connection->getDatabaseName() === DB::connection($default)->getDatabaseName()) {
            throw new OrganisationMigrationRefused('The organisation migration set never runs on the central database.');
        }

        return $name;
    }
}
