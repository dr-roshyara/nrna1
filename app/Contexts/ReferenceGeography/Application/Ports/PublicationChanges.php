<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Ports;

use App\Contexts\ReferenceGeography\Domain\SlotKey;

/** One committed publication entry and the claims it published (slot and value only), for the feed (G-5c). */
final readonly class PublicationChanges
{
    /** @param list<array{0: SlotKey, 1: ?string}> $changes value null = NONE (a withdrawal or reversal) */
    public function __construct(
        public int $position,
        public string $recordedAt,
        public string $kind,
        public ?string $revisionId,
        public ?int $contentVersion,
        public string $governanceRule,
        public array $changes,
    ) {
    }
}
