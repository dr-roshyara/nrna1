<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\Contracts;

interface CommandBusInterface
{
    public function dispatch(object $command): void;
}
