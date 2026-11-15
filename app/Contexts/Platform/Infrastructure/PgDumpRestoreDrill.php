<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure;

use App\Contexts\Platform\Application\Ports\OrganisationDatabaseFacts;
use App\Contexts\Platform\Application\Ports\OrganisationRestoreDrill;
use App\Contexts\Platform\Application\Ports\ServedOrganisationRepository;
use App\Contexts\Platform\Application\ProvisioningFailed;
use App\Contexts\Platform\Application\ResolvedOrganisation;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

/**
 * The restore drill (PBDIGIT-345 task 12b; T-17; D-234 Option A), with PostgreSQL's own tools:
 * 1. `pg_dump -Fc` of the organisation's database (at its REGISTRY locator) into an owner-only file;
 * 2. a SCRATCH database `scratch_restore_<random>` on the same cluster: never written to the registry, so it can
 *    never be routed; `pg_restore` the backup into it;
 * 3. the L-1 facts read ON THE COPY (PostgresOrganisationReadinessProbe::factsOn): identity = the registry id,
 *    the organisation set recorded, Membership's readiness answer. Technical metadata only; nothing else is read;
 * 4. finally, always: the scratch database dropped and the backup file deleted.
 * It never writes the registry. A failing dump or restore → ProvisioningFailed (no credential in the message).
 */
final class PgDumpRestoreDrill implements OrganisationRestoreDrill
{
    /**
     * @param list<string> $toolPrefix how the PostgreSQL client tools are started. [] = `pg_dump` / `pg_restore` on PATH,
     *        which MUST be of the server's major version (a newer pg_dump writes settings an older server rejects).
     *        Otherwise a prefix such as ['podman', 'exec', '-i', '-e', 'PGPASSWORD', '<container>']: the dump then
     *        streams through stdout and stdin, and $toolHost / $toolPort are the server as seen from there.
     */
    public function __construct(
        private readonly ServedOrganisationRepository $registry,
        private readonly OrganisationConnectionFactory $factory,
        private readonly PostgresOrganisationReadinessProbe $probe,
        private readonly string $workDirectory,
        private readonly array $toolPrefix = [],
        private readonly ?string $toolHost = null,
        private readonly ?string $toolPort = null,
        private readonly float $timeoutSeconds = 900.0,
    ) {
    }

    public function rehearse(TenantId $id): OrganisationDatabaseFacts
    {
        $locator = $this->registry->find($id)?->locator() ?? throw new ProvisioningFailed("Organisation {$id->value()} has no database to back up.");
        $p = $this->factory->parameters($locator);
        $scratch = 'scratch_restore_' . bin2hex(random_bytes(6));
        $dump = $this->workDirectory . '/drill-' . bin2hex(random_bytes(6)) . '.dump';
        $context = $this->factory->establish(new ResolvedOrganisation($id, $locator));   // to create and drop the scratch database
        $scratchConnection = 'drill_' . $scratch;
        try {
            touch($dump);
            chmod($dump, 0600);
            $this->dump($p, $dump);
            $context->connection->statement("CREATE DATABASE {$scratch}");
            $this->restore($p, $dump, $scratch);
            config(["database.connections.{$scratchConnection}" => ['driver' => 'pgsql', 'host' => $p['host'], 'port' => $p['port'], 'database' => $scratch,
                'username' => $p['username'], 'password' => $p['password'], 'charset' => 'utf8', 'prefix' => '', 'search_path' => 'public', 'sslmode' => $p['sslmode']]]);

            return $this->probe->factsOn(DB::connection($scratchConnection), $id);
        } finally {
            DB::purge($scratchConnection);
            config(["database.connections.{$scratchConnection}" => null]);
            $context->connection->statement("DROP DATABASE IF EXISTS {$scratch} WITH (FORCE)");
            $this->factory->release($context);
            if (is_file($dump)) {
                unlink($dump);
            }
        }
    }

    /** @param array{host: string, port: string, username: string, password: string, database: string, sslmode: string} $p */
    private function server(array $p): array
    {
        return ['-h', $this->toolHost ?? $p['host'], '-p', $this->toolPort ?? $p['port'], '-U', $p['username']];
    }

    /** @param array{host: string, port: string, username: string, password: string, database: string, sslmode: string} $p */
    private function dump(array $p, string $file): void
    {
        if ($this->toolPrefix === []) {
            $this->run(['pg_dump', '-Fc', ...$this->server($p), '-d', $p['database'], '-f', $file], $p, 'backup');

            return;
        }
        $out = fopen($file, 'wb');
        try {
            $this->run([...$this->toolPrefix, 'pg_dump', '-Fc', ...$this->server($p), '-d', $p['database']], $p, 'backup',
                static function (string $type, string $chunk) use ($out): void {
                    if ($type === Process::OUT) {
                        fwrite($out, $chunk);
                    }
                });
        } finally {
            fclose($out);
        }
    }

    /** @param array{host: string, port: string, username: string, password: string, database: string, sslmode: string} $p */
    private function restore(array $p, string $file, string $database): void
    {
        $args = ['pg_restore', '--no-owner', '--exit-on-error', ...$this->server($p), '-d', $database];
        if ($this->toolPrefix === []) {
            $this->run([...$args, $file], $p, 'restore');

            return;
        }
        $in = fopen($file, 'rb');
        try {
            $this->run([...$this->toolPrefix, ...$args], $p, 'restore', null, $in);
        } finally {
            fclose($in);
        }
    }

    /**
     * @param list<string> $command
     * @param array{password: string, sslmode: string} $p
     * @param resource|null $input
     */
    private function run(array $command, array $p, string $step, ?\Closure $onOutput = null, $input = null): void
    {
        $process = new Process($command, null, ['PGPASSWORD' => $p['password'], 'PGSSLMODE' => $p['sslmode']], $input, $this->timeoutSeconds);
        $process->run($onOutput);
        if (!$process->isSuccessful()) {
            $lines = array_values(array_filter(array_map('trim', explode("\n", $process->getErrorOutput()))));
            $reason = str_replace($p['password'], '[redacted]', $lines === [] ? 'no message' : (string) end($lines));

            throw new ProvisioningFailed("The drill's {$step} failed (exit {$process->getExitCode()}): " . mb_substr($reason, 0, 300));
        }
    }
}
