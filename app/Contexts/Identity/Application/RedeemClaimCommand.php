<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application;

/**
 * I-3: a signed-in person presents a claim code. `clientKey` identifies the client for throttling (for example the
 * request's IP address, supplied by the delivery layer); Identity stores only its hash.
 */
final readonly class RedeemClaimCommand
{
    public function __construct(public string $typedCode, public string $clientKey)
    {
    }
}
