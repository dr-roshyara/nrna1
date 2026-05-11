<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Resolution;

use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;

final readonly class FinalAuthorityDecision
{
    public function __construct(
        public ?JurisdictionNode $winningNode,
        public AuthorityResolutionType $type,
        public string $resolutionReason,
    ) {}

    public static function none(): self
    {
        return new self(null, AuthorityResolutionType::NONE, 'no_authority_found');
    }

    public static function from(JurisdictionNode $node, AuthorityResolutionType $type, string $reason): self
    {
        return new self($node, $type, $reason);
    }

    public function isResolved(): bool
    {
        return $this->type !== AuthorityResolutionType::NONE;
    }
}
