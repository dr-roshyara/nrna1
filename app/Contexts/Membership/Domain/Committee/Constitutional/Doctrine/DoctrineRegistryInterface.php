<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Doctrine;

/**
 * Extension point for historical doctrine lookup.
 * Replay already stores doctrineVersion, legitimacyPolicyVersion, arbitrationPolicyVersion
 * in every snapshot — this registry resolves those version strings to immutable doctrine artifacts.
 * Concrete implementation deferred to GEO-3.4 when the doctrine registry materializes.
 */
interface DoctrineRegistryInterface
{
    public function resolveVersion(string $doctrineVersion): ?object;
}
