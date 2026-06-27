<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Resolution;

use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;

interface AuthoritySelectionPolicy
{
    /**
     * Select a winning authority from multiple candidates.
     *
     * @param JurisdictionNode[] $nodes - guaranteed non-empty
     */
    public function select(array $nodes): JurisdictionNode;
}
