<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Infrastructure\Identity;

use App\Contexts\Contestation\Application\Port\IdentityGenerator;
use Illuminate\Support\Str;

final class UuidIdentityGenerator implements IdentityGenerator
{
    public function next(): string
    {
        return (string) Str::uuid();
    }
}
