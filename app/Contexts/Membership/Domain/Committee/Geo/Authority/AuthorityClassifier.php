<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Authority;

use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\DelegationType;

final class AuthorityClassifier
{
    /**
     * Classify a root node and reachable jurisdictions into semantic authority buckets.
     *
     * @param JurisdictionNode|null $rootNode The root (direct) authority
     * @param array $reachable Array of ['node' => JurisdictionNode, 'edge' => DelegationEdge] pairs
     * @return AuthorityClassification
     */
    public function classify(?JurisdictionNode $rootNode, array $reachable): AuthorityClassification
    {
        $delegated = [];
        $overrides = [];
        $exceptions = [];

        foreach ($reachable as $pair) {
            $node = $pair['node'];
            $edge = $pair['edge'];

            if ($node->isExceptionZone) {
                $exceptions[] = $node;
            } elseif ($edge->type === DelegationType::OVERRIDE) {
                $overrides[] = $node;
            } else {
                $delegated[] = $node;
            }
        }

        return new AuthorityClassification($rootNode, $delegated, $overrides, $exceptions);
    }
}
