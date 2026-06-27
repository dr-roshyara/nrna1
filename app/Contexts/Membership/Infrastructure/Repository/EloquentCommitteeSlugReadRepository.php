<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Repository;

use App\Contexts\Membership\Domain\Committee\Repository\CommitteeSlugReadRepository;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;

final class EloquentCommitteeSlugReadRepository implements CommitteeSlugReadRepository
{
    public function slugExists(string $tenantId, string $slug): bool
    {
        return CommitteeModel::withoutGlobalScopes()
            ->where('organisation_id', $tenantId)
            ->where('slug', $slug)
            ->exists();
    }

    public function findSlugsLike(string $tenantId, string $baseSlug): array
    {
        return CommitteeModel::withoutGlobalScopes()
            ->where('organisation_id', $tenantId)
            ->where('slug', 'LIKE', $baseSlug . '%')
            ->pluck('slug')
            ->all();
    }
}
