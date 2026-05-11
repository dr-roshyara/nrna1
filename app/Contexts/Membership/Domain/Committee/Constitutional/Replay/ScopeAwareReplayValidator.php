<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Replay;

use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalScope;

final class ScopeAwareReplayValidator
{
    public function validate(?ConstitutionalScope $snapshotScope, ConstitutionalScope $replayScope): void
    {
        // Null snapshot scope (unconstrained) can be replayed under any scope
        if ($snapshotScope === null) {
            return;
        }

        // If scopes don't match, raise exception
        if ($snapshotScope->value !== $replayScope->value) {
            throw new ScopeMismatchException($snapshotScope->value, $replayScope->value);
        }
    }
}
