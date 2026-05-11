<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Replay;

final class ScopeMismatchException extends \DomainException
{
    public function __construct(?string $snapshotScope, ?string $replayScope)
    {
        $snapshotScopeStr = $snapshotScope ?? 'unconstrained';
        $replayScopeStr = $replayScope ?? 'unconstrained';
        parent::__construct(
            "Scope mismatch: snapshot scope '{$snapshotScopeStr}' cannot be replayed under scope '{$replayScopeStr}'"
        );
    }
}
