<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * Sets aside an earlier decision fact ex tunc (correction contract §3.1). It changes only
 * the target's liveness and creates no consequences (D-COR-5 (A)); it has no valid time.
 */
final readonly class Annul implements Fact, ReversalFact
{
    public function __construct(
        private FactId $id,
        private FactId $target,
        private Provenance $provenance,
        private ?Instant $decidedAt = null,
    ) {
    }

    public function id(): FactId
    {
        return $this->id;
    }

    public function target(): FactId
    {
        return $this->target;
    }

    public function decidedAt(): ?Instant
    {
        return $this->decidedAt;
    }

    public function provenance(): Provenance
    {
        return $this->provenance;
    }
}
