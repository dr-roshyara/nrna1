<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Infrastructure;

use App\Contexts\ReferenceGeography\Application\Ports\PublishedClaims;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use Illuminate\Database\ConnectionInterface;

/** A claim is published iff a refgeo_publications row names its (revision, content version) (G-4; G-6b). */
final class PostgresPublishedClaims implements PublishedClaims
{
    public function __construct(private readonly ConnectionInterface $db)
    {
    }

    public function isPublished(ClaimId $claim): bool
    {
        return $this->db->selectOne('SELECT 1 AS x FROM refgeo_claims c JOIN refgeo_publications p ON p.revision_id = c.revision_id AND p.content_version = c.content_version
            WHERE c.claim_id = ?::uuid LIMIT 1', [$claim->value()]) !== null;
    }
}
