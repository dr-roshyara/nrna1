<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure;

/** PBDIGIT-345 task 5b: an organisation database was not prepared; the message says why (never a credential). */
final class OrganisationProvisioningRefused extends \RuntimeException
{
}
