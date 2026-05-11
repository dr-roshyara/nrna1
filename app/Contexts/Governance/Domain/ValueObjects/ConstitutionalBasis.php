<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\ValueObjects;

use DomainException;

final readonly class ConstitutionalBasis
{
    private function __construct(
        private string $article,
        private ?string $referenceText,
    ) {}

    public static function from(
        string $article,
        ?string $referenceText = null,
    ): self {
        if (empty(trim($article))) {
            throw new DomainException('Article reference cannot be empty');
        }

        return new self($article, $referenceText);
    }

    public function article(): string
    {
        return $this->article;
    }

    public function referenceText(): ?string
    {
        return $this->referenceText;
    }

    public function equals(self $other): bool
    {
        return $this->article === $other->article
            && $this->referenceText === $other->referenceText;
    }
}
