<?php

namespace App\Domain\Election\Replay;

/**
 * ReplayCompatibilityVersion
 *
 * Schema version marker for replay evidence envelopes.
 * Ensures that a replay session created under one schema version
 * is not replayed under a different version — which would produce
 * different outcomes and violate the replay determinism invariant.
 *
 * INVARIANT:
 * ReplayCertification MUST fail if the compatibility version at
 * replay time differs from the version at original evaluation time.
 */
readonly class ReplayCompatibilityVersion
{
    private function __construct(
        public int $major,
        public int $minor,
    ) {}

    public static function current(): self
    {
        return new self(major: 1, minor: 0);
    }

    public static function fromString(string $version): self
    {
        $parts = explode('.', $version);
        if (count($parts) !== 2 || !is_numeric($parts[0]) || !is_numeric($parts[1])) {
            throw new \InvalidArgumentException(
                "Invalid compatibility version format: {$version}. Expected 'major.minor'."
            );
        }
        return new self((int)$parts[0], (int)$parts[1]);
    }

    public function toString(): string
    {
        return "{$this->major}.{$this->minor}";
    }

    /**
     * Two versions are compatible if major versions match.
     * A minor version increase implies backward-compatible change.
     */
    public function isCompatibleWith(self $other): bool
    {
        return $this->major === $other->major;
    }

    /**
     * Identical versions are strictly equal.
     */
    public function equals(self $other): bool
    {
        return $this->major === $other->major && $this->minor === $other->minor;
    }
}
