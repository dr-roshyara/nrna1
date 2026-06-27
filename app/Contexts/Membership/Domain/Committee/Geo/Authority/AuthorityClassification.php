<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Authority;

use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;

final readonly class AuthorityClassification
{
    /**
     * @param JurisdictionNode[] $delegated
     * @param JurisdictionNode[] $overrides
     * @param JurisdictionNode[] $exceptions
     */
    public function __construct(
        public ?JurisdictionNode $direct,
        public array $delegated,
        public array $overrides,
        public array $exceptions,
    ) {}

    public function isEmpty(): bool
    {
        return $this->direct === null
            && empty($this->delegated)
            && empty($this->overrides)
            && empty($this->exceptions);
    }
}
