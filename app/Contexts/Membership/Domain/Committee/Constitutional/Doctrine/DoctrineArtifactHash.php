<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Doctrine;

final readonly class DoctrineArtifactHash
{
    private function __construct(private string $value) {}

    public static function fromArtifact(DoctrineArtifact $artifact): self
    {
        // Canonical sort rules for deterministic hashing
        $sortedRules = $artifact->doctrineRules;
        sort($sortedRules);

        // Build canonical string for hashing
        $canonical = implode('|', [
            $artifact->doctrineId,
            $artifact->version,
            $artifact->constitutionalScope->value,
            $artifact->effectiveFrom->format(\DateTimeInterface::ATOM),
            $artifact->effectiveUntil?->format(\DateTimeInterface::ATOM) ?? 'null',
            json_encode($sortedRules, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);

        $hash = hash('sha256', $canonical);
        return new self($hash);
    }

    public function verify(DoctrineArtifact $artifact): bool
    {
        $recomputed = self::fromArtifact($artifact);
        return $this->value === $recomputed->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
