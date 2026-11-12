<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Domain;

/**
 * Where an organisation's own database lives (D-193 Part 4; delta §3.3, §3.9): the cluster and the database name,
 * plus a REFERENCE to the credential (environment or secret store), never the password itself.
 * Written only by provisioning (ISO-2); naming the cluster lets one organisation move to its own cluster later by a
 * locator change, without a model change (D-219 (4)).
 */
final readonly class DatabaseLocator
{
    public function __construct(
        public string $cluster,
        public string $database,
        public string $credentialRef,
    ) {
        if (trim($cluster) === '') {
            throw new \InvalidArgumentException('A database locator needs its cluster.');
        }
        if (preg_match('/^[a-z_][a-z0-9_]{0,62}$/', $database) !== 1) {
            throw new \InvalidArgumentException('A database name must be a plain PostgreSQL identifier.');
        }
        if (trim($credentialRef) === '') {
            throw new \InvalidArgumentException('A database locator needs a credential reference.');
        }
    }

    public function equals(self $other): bool
    {
        return $this->cluster === $other->cluster
            && $this->database === $other->database
            && $this->credentialRef === $other->credentialRef;
    }
}
