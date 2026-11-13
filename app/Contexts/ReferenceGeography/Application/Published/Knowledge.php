<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Published;

/**
 * "As officially known at": the token a consumer records with a decision that used Geography (v3.11 §4, §6).
 * Asking again with the same position always gives the same answer. Every query re-checks it, so a hand-made
 * Knowledge beyond the boundary is refused, never answered.
 */
final readonly class Knowledge
{
    public function __construct(public int $position, public ?string $asOfTime = null)
    {
    }
}
