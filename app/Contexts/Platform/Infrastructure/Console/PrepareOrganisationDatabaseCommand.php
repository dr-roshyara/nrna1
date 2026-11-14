<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure\Console;

use App\Contexts\Platform\Infrastructure\DedicatedProvisioningProcess;
use App\Contexts\Platform\Infrastructure\OrganisationDatabasePreparer;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Console\Command;

/**
 * PBDIGIT-345 task 5b: the DEDICATED PROVISIONING PROCESS of one organisation (founding step 3). Started by
 * ProcessOrganisationProvisioner; an administrator does not run it by hand (founding is driven by FoundOrganisation).
 * Exit 0 = prepared; otherwise exit 1 and one line on stderr saying why (never a credential).
 */
final class PrepareOrganisationDatabaseCommand extends Command
{
    protected $signature = 'platform:prepare-organisation-database {organisation : the organisation id (UUID)}';

    protected $description = 'Founding step 3: prepare one organisation\'s own database (its dedicated process).';

    public function handle(OrganisationDatabasePreparer $preparer): int
    {
        try {
            $id = TenantId::fromString((string) $this->argument('organisation'));
            DedicatedProvisioningProcess::enter($id);
            $preparer->prepare($id);
        } catch (\Throwable $e) {
            $this->getOutput()->getErrorStyle()->writeln(str_replace(["\r", "\n"], ' ', $e::class . ': ' . $e->getMessage()));

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
