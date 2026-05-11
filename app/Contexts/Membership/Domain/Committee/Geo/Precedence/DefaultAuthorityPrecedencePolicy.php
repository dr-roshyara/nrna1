<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Precedence;

use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassification;

final class DefaultAuthorityPrecedencePolicy implements AuthorityPrecedencePolicy
{
    public function rank(AuthorityClassification $classification): array
    {
        $ranked = [];

        if ($classification->direct) {
            $ranked[] = ['node' => $classification->direct, 'score' => 60];
        }

        foreach ($classification->delegated as $node) {
            $ranked[] = ['node' => $node, 'score' => 40];
        }

        foreach ($classification->overrides as $node) {
            $ranked[] = ['node' => $node, 'score' => 80];
        }

        foreach ($classification->exceptions as $node) {
            $ranked[] = ['node' => $node, 'score' => 100];
        }

        usort($ranked, fn($a, $b) => $b['score'] <=> $a['score']);

        return $ranked;
    }
}
