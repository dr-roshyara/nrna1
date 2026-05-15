<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee;

use App\Contexts\Membership\Application\Committee\DTOs\SlugAvailabilityResult;
use App\Contexts\Membership\Domain\Committee\Repository\CommitteeSlugReadRepository;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeSlug;

final class CommitteeSlugAvailabilityService
{
    private const RESERVED = [
        'admin', 'api', 'dashboard', 'settings', 'committee',
        'committees', 'create', 'edit', 'delete', 'system',
        'root', 'login', 'logout', 'register',
    ];

    public function __construct(
        private readonly CommitteeSlugReadRepository $repository
    ) {}

    public function check(string $tenantId, CommitteeSlug $slug): SlugAvailabilityResult
    {
        $slugValue = $slug->value();

        if ($this->isReserved($slugValue)) {
            return new SlugAvailabilityResult(
                exists: true,
                reserved: true,
                slug: $slugValue,
                suggestions: [],
            );
        }

        $exists = $this->repository->slugExists($tenantId, $slugValue);

        return new SlugAvailabilityResult(
            exists: $exists,
            reserved: false,
            slug: $slugValue,
            suggestions: $exists ? $this->suggest($tenantId, $slugValue) : [],
        );
    }

    private function isReserved(string $slug): bool
    {
        return in_array($slug, self::RESERVED, true);
    }

    private function suggest(string $tenantId, string $base): array
    {
        $suggestions = [];
        $taken = $this->repository->findSlugsLike($tenantId, $base);
        $takenSet = array_flip($taken);

        for ($i = 2; $i <= 15 && count($suggestions) < 3; $i++) {
            $candidate = $base . '-' . $i;
            if (!isset($takenSet[$candidate])) {
                $suggestions[] = $candidate;
            }
        }

        foreach (['global', 'central', 'executive', 'main'] as $suffix) {
            if (count($suggestions) >= 3) break;
            $candidate = $base . '-' . $suffix;
            if (!isset($takenSet[$candidate])) {
                $suggestions[] = $candidate;
            }
        }

        return $suggestions;
    }
}
