<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Determination;

use InvalidArgumentException;

/**
 * Opaque reference to the evidence the determination is based on (50-05
 * `evidenceEnvelopeRef`) — the EvidenceEnvelope's hash, owned by the Evidence
 * context. Reference only (TP-1); carries no vote content and no voter linkage.
 */
final readonly class EvidenceEnvelopeRef
{
    private function __construct(public string $value)
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException('A determination requires an evidence reference.');
        }
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
