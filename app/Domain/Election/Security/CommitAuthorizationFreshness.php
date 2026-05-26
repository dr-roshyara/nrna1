<?php

namespace App\Domain\Election\Security;

readonly class CommitAuthorizationFreshness
{
    public function __construct(
        public string              $commitTokenHash,
        public string              $viewTokenHash,
        public \DateTimeImmutable  $issuedAt,
        public bool                $isUsed,
        public ?\DateTimeImmutable $usedAt,
    ) {}

    public function isFresh(int $maxAgeSeconds = 3600): bool
    {
        if ($this->isUsed) {
            return false;
        }

        $now = new \DateTimeImmutable();
        $ageInSeconds = $now->getTimestamp() - $this->issuedAt->getTimestamp();

        return $ageInSeconds <= $maxAgeSeconds;
    }

    public function isLinkedToView(string $viewTokenHash): bool
    {
        return $this->viewTokenHash === $viewTokenHash;
    }

    public function isReplay(): bool
    {
        return $this->isUsed && $this->usedAt !== null;
    }
}
