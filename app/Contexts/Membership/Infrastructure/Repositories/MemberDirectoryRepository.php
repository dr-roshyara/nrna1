<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Repositories;

use App\Contexts\Membership\Application\DTO\MemberDirectory;

class MemberDirectoryRepository implements MemberDirectoryRepositoryInterface
{
    public function upsert(MemberDirectory $projection): void
    {
        // TODO: Implement persistence to member_directory table
    }
}
