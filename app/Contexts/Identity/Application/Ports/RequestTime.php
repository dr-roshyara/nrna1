<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application\Ports;

/**
 * The current request's start and the present instant (I-TDD-02 §10.1 freshness: an AuthorityCheck answer must be
 * checked within the same request as the claim call; evaluatedFor must be the act's date). Not the expiry clock:
 * claim expiry uses the central database's clock_timestamp() under the locks (§5).
 */
interface RequestTime
{
    public function requestStart(): \DateTimeImmutable;

    public function currentInstant(): \DateTimeImmutable;
}
