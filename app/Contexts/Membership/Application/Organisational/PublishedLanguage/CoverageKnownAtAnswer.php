<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\PublishedLanguage;

/** C2's answer: complete (every key answered at τ ≤ closedThrough) or refused. Never partial. */
final readonly class CoverageKnownAtAnswer
{
    /** @param list<CoverageKnownAtItem> $items */
    private function __construct(
        private ?CoverageKnownAtRefusal $refusal,
        private ?int $closedThroughMicros,
        private array $items,
    ) {
    }

    /** @param list<CoverageKnownAtItem> $items */
    public static function complete(int $closedThroughMicros, array $items): self
    {
        return new self(null, $closedThroughMicros, $items);
    }

    public static function refused(CoverageKnownAtRefusal $reason, ?int $closedThroughMicros = null): self
    {
        return new self($reason, $closedThroughMicros, []);
    }

    public function isComplete(): bool { return $this->refusal === null; }
    public function refusal(): ?CoverageKnownAtRefusal { return $this->refusal; }
    public function closedThroughMicros(): ?int { return $this->closedThroughMicros; }

    /** @return list<CoverageKnownAtItem> */
    public function items(): array
    {
        if ($this->refusal !== null) {
            throw new \LogicException('A refused C2 answer has no items; a refusal is never "not a member".');
        }

        return $this->items;
    }
}
