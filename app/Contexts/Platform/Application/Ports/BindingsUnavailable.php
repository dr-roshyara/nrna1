<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application\Ports;

/** Task 6: Identity's bindings could not be read; no organisation is entered (fail closed). */
final class BindingsUnavailable extends \RuntimeException
{
}
