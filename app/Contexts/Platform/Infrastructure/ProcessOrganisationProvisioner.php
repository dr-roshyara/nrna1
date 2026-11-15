<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure;

use App\Contexts\Platform\Application\Ports\OrganisationProvisioner;
use App\Contexts\Platform\Application\Ports\OrganisationSchemaRepair;
use App\Contexts\Platform\Application\ProvisioningFailed;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Symfony\Component\Process\Process;

/**
 * PBDIGIT-345 task 5b: founding step 3 in its OWN process (task 4 limit): starts `platform:prepare-organisation-database`
 * for one organisation and waits. The child inherits this process's environment; `$env` adds or replaces variables
 * (tests point every connection at the isolated cluster). A failure is reported with the child's last error line, with
 * every password-like value of the environment removed.
 */
final class ProcessOrganisationProvisioner implements OrganisationProvisioner, OrganisationSchemaRepair
{
    /** @param array<string, string> $env */
    public function __construct(
        private readonly string $basePath,
        private readonly array $env = [],
        private readonly float $timeoutSeconds = 600.0,
    ) {
    }

    public function prepare(TenantId $id): void
    {
        $this->runChild($id, []);
    }

    /** Task 11 (E-4 migration repair): the same dedicated process, in its repair mode */
    public function repair(TenantId $id): void
    {
        $this->runChild($id, ['--repair']);
    }

    /** @param list<string> $options */
    private function runChild(TenantId $id, array $options): void
    {
        $process = new Process([PHP_BINARY, $this->basePath . '/artisan', 'platform:prepare-organisation-database', $id->value(), ...$options, '--no-interaction'],
            $this->basePath, $this->env === [] ? null : $this->env, null, $this->timeoutSeconds);
        $process->run();
        if ($process->isSuccessful()) {
            return;
        }
        $lines = array_values(array_filter(array_map('trim', explode("\n", $process->getErrorOutput() . "\n" . $process->getOutput()))));
        $reason = $lines === [] ? 'no message' : end($lines);

        throw new ProvisioningFailed("Preparing organisation {$id->value()}'s database failed (exit {$process->getExitCode()}): " . $this->redact($reason));
    }

    private function redact(string $text): string
    {
        foreach ($this->env + getenv() as $name => $value) {
            if (is_string($value) && strlen($value) >= 4 && preg_match('/PASSWORD|SECRET/i', (string) $name) === 1) {
                $text = str_replace($value, '[redacted]', $text);
            }
        }

        return mb_substr($text, 0, 500);
    }
}
