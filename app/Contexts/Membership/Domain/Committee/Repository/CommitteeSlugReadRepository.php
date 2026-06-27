<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Repository;

interface CommitteeSlugReadRepository
{
    public function slugExists(string $tenantId, string $slug): bool;

    public function findSlugsLike(string $tenantId, string $baseSlug): array;
}
