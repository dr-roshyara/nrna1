<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Exceptions;

abstract class DomainKernelException extends \RuntimeException
{
    abstract public function code(): string;
}
