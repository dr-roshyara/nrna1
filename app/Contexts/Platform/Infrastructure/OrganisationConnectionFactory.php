<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure;

use App\Contexts\Platform\Application\ResolvedOrganisation;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;

/**
 * One named connection per organisation (PBDIGIT-345 task 3; delta §3.3 C-1, C-2; D-222: custom, no Spatie).
 * - The name is derived from the organisation id only: never a shared, re-pointed name such as 'tenant'.
 * - Built from the registry locator: the cluster's host and port from configuration; the login from its reference.
 * - Before it is handed out, the connection must be on the locator's database (current_database()).
 * - The default (central) connection is never touched.
 * - release() purges it: in a long-running worker no organisation connection survives its operation (C-2).
 */
final class OrganisationConnectionFactory
{
    /** @param array<string, array{host: string, port: string|int, sslmode?: string}> $clusters */
    public function __construct(private readonly array $clusters, private readonly EnvironmentDatabaseLogin $login)
    {
    }

    public static function connectionName(TenantId $organisation): string
    {
        return 'org_' . str_replace('-', '', strtolower($organisation->value()));
    }

    public function establish(ResolvedOrganisation $resolved): OrganisationContext
    {
        $locator = $resolved->locator;
        $cluster = $this->clusters[$locator->cluster] ?? null;
        if ($cluster === null) {
            throw new OrganisationRoutingRefused("Unknown database cluster '{$locator->cluster}'.");
        }
        $login = $this->login->resolve($locator->credentialRef);
        $name = self::connectionName($resolved->organisationId);

        DB::purge($name);
        config(["database.connections.{$name}" => [
            'driver' => 'pgsql',
            'host' => $cluster['host'],
            'port' => (string) $cluster['port'],
            'database' => $locator->database,
            'username' => $login['username'],
            'password' => $login['password'],
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => $cluster['sslmode'] ?? 'prefer',
        ]]);
        try {
            /** @var Connection $connection */
            $connection = DB::connection($name);
            $database = $connection->selectOne('SELECT current_database() AS d')->d;
        } catch (\Throwable $e) {
            // task 12 (T-11, T-18): an unreachable database leaves NOTHING registered behind (no stale connection)
            $this->forget($name);
            throw $e;
        }
        if ($database !== $locator->database) {
            $this->forget($name);
            throw new OrganisationRoutingRefused('The organisation connection is not on its registry database.');
        }

        return new OrganisationContext($resolved->organisationId, $connection);
    }

    /**
     * Task 12b (T-17): the connection parameters behind a locator, for Platform's own Infrastructure tools that run
     * outside PHP (pg_dump, pg_restore in the restore drill). Never logged; never handed to another context.
     *
     * @return array{host: string, port: string, database: string, username: string, password: string, sslmode: string}
     */
    public function parameters(\App\Contexts\Platform\Domain\DatabaseLocator $locator): array
    {
        $cluster = $this->clusters[$locator->cluster] ?? throw new OrganisationRoutingRefused("Unknown database cluster '{$locator->cluster}'.");
        $login = $this->login->resolve($locator->credentialRef);

        return ['host' => (string) $cluster['host'], 'port' => (string) $cluster['port'], 'database' => $locator->database,
            'username' => $login['username'], 'password' => $login['password'], 'sslmode' => (string) ($cluster['sslmode'] ?? 'prefer')];
    }

    public function release(OrganisationContext $context): void
    {
        $this->forget($context->connection->getName());
    }

    private function forget(string $name): void
    {
        DB::purge($name);
        config(["database.connections.{$name}" => null]);
    }
}
