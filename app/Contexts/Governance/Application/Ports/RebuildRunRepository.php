<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\Ports;

use DateTimeImmutable;

interface RebuildRunRepository
{
    public function start(string $generation, ?string $tenantId, DateTimeImmutable $now, int $total): void;

    public function complete(string $generation, int $rebuilt, int $failed, DateTimeImmutable $now): void;

    public function fail(string $generation, string $error): void;
}
