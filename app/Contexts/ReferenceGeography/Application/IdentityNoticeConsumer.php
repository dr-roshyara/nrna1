<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Application\Ports\StewardRosterStore;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\StewardRoster;

/**
 * Applies Identity's merge and reversal notices to Geography's roster (completion §5 IG-4, IG-5; G-4 interpretation §4).
 * Local only: no transaction spans Identity and Geography. Each notice is applied once, under the publication lock;
 * a duplicate returns null and writes nothing. Publication then judges independence "as known to Identity at roster
 * version v": exactly the notices applied by v.
 */
final class IdentityNoticeConsumer
{
    public function __construct(private readonly StewardRosterStore $store)
    {
    }

    /** @param list<PersonReference> $persons */
    public function merged(string $mergeId, array $persons): ?int
    {
        return $this->store->writeUnderLock(static fn (StewardRoster $r): ?int => $r->recordMerge($mergeId, $persons));
    }

    public function reversed(string $mergeId): ?int
    {
        return $this->store->writeUnderLock(static fn (StewardRoster $r): ?int => $r->recordReversal($mergeId));
    }
}
