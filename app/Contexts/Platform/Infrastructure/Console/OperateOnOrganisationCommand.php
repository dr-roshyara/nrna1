<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure\Console;

use App\Contexts\Platform\Application\OperateTechnically;
use Illuminate\Console\Command;

/**
 * The operator channel E-4 (PBDIGIT-345 task 11; §3.8; D-166 (1), D-219 (3)): ONE technical operation on ONE explicit
 * organisation, under its joining grant, recorded and reported. Release 1 knows one operation: `migration-repair`.
 * Anything else is content access, which needs a case grant and is NOT offered: refused. Exit 0 only on success.
 *   php artisan platform:operate <organisation-id> migration-repair --operator="ops:<who>"
 */
final class OperateOnOrganisationCommand extends Command
{
    protected $signature = 'platform:operate {organisation : the organisation id (UUID)} {operation : migration-repair}
        {--operator= : who runs it (recorded; it grants nothing)}';

    protected $description = 'E-4: a technical operation on one organisation (no business content), recorded and reported.';

    public function handle(OperateTechnically $operate): int
    {
        $outcome = $operate->perform((string) $this->argument('organisation'), (string) $this->argument('operation'), (string) $this->option('operator'));
        $line = "{$outcome->operationId} {$outcome->status}" . ($outcome->reason === null ? '' : ": {$outcome->reason}");
        $outcome->status === 'succeeded' ? $this->info($line) : $this->error($line);

        return $outcome->status === 'succeeded' ? self::SUCCESS : self::FAILURE;
    }
}
