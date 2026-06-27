<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Infrastructure\Identity;

use App\Contexts\Adjudication\Application\Port\IdentityGenerator;
use Illuminate\Support\Str;

final class UuidIdentityGenerator implements IdentityGenerator
{
    public function next(): string
    {
        return (string) Str::uuid();
    }
}
