<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Precedence;

use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassification;

interface AuthorityPrecedencePolicy
{
    /**
     * Rank authorities in classification by precedence score.
     * Returns array of ['node' => JurisdictionNode, 'score' => int] sorted DESC by score.
     * Informational only — not a decision gate.
     *
     * @return array<array{node: \App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode, score: int}>
     */
    public function rank(AuthorityClassification $classification): array;
}
