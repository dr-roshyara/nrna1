<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Repositories;

use App\Contexts\Membership\Application\DTO\MemberDirectory;

interface MemberDirectoryRepositoryInterface
{
    public function upsert(MemberDirectory $projection): void;
}
