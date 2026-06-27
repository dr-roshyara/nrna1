<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Doctrine;

final class ConstitutionalArticleReferenceCollection
{
    /** @var ConstitutionalArticleReference[] */
    private array $references = [];

    public function add(ConstitutionalArticleReference $reference): self
    {
        $new = new self();
        $new->references = [...$this->references, $reference];
        return $new;
    }

    public function filterByScope(?string $scope): self
    {
        $new = new self();
        foreach ($this->references as $ref) {
            if ($ref->scopeContext === $scope) {
                $new->references[] = $ref;
            }
        }
        return $new;
    }

    public function toArticleCodes(): array
    {
        return array_map(fn($ref) => $ref->articleId, $this->references);
    }

    public function count(): int
    {
        return count($this->references);
    }
}
