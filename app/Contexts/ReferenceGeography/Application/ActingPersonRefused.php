<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

/** The acting person cannot be established (not signed in, account not active, Identity unavailable): no act. */
final class ActingPersonRefused extends \RuntimeException
{
    public function __construct(public readonly string $reason)
    {
        parent::__construct("Acting person refused: {$reason}");
    }
}
