<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Conflict;

final readonly class AuthorityConflict
{
    /**
     * @param string[] $authorityIds
     */
    public function __construct(
        public ConflictType $type,
        public array $authorityIds,
        public string $description,
    ) {}
}
