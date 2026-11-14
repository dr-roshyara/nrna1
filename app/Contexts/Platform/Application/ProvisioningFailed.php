<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application;

/** PBDIGIT-345 task 5b: the organisation database could not be prepared. The message never carries a credential. */
final class ProvisioningFailed extends \RuntimeException
{
}
