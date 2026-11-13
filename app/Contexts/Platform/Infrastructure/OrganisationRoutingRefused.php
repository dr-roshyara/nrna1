<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure;

/** An organisation connection could not be established (task 3). Content-free: never a secret or a password. */
final class OrganisationRoutingRefused extends \RuntimeException
{
}
