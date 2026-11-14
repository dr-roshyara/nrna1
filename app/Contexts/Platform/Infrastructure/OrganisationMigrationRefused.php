<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure;

/** PBDIGIT-345 task 4: the organisation migration set was asked to run outside an organisation's own database. */
final class OrganisationMigrationRefused extends \RuntimeException
{
}
