<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Doctrine;

final readonly class ConstitutionalArticleReference
{
    public function __construct(
        public string $articleId,
        public string $title,
        public ?string $scopeContext = null,
    ) {}

    public static function fromCode(string $code): self
    {
        return new self(articleId: $code, title: $code);
    }
}
