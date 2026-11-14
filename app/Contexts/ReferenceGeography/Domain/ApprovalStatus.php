<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** The approval status of one publication as known at a position, with its full ordered history (design §10). */
final readonly class ApprovalStatus
{
    /** @param list<array{position: int, event: string}> $history */
    public function __construct(public ApprovalStatusKind $kind, public ?int $correctedAt, public array $history)
    {
    }
}
