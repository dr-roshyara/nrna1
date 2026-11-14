<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application\Ports;

/** Task 5c: the contacts' accounts could not be checked; the organisation is not made operational (fail closed). */
final class ContactAccountsUnavailable extends \RuntimeException
{
}
