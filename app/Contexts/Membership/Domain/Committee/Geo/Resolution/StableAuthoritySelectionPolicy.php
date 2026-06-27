<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Resolution;

use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;

final class StableAuthoritySelectionPolicy implements AuthoritySelectionPolicy
{
    public function select(array $nodes): JurisdictionNode
    {
        usort($nodes, fn($a, $b) => strcmp($a->id, $b->id));
        return $nodes[0];
    }
}
