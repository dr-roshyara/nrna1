<?php

namespace App\Domain\Election\Enum;

enum ElectionRole: string
{
    case Chief = 'chief';
    case Deputy = 'deputy';
    case SuperAdmin = 'super_admin';
    case PlatformAdmin = 'platform_admin';
    case System = 'system';
    case Admin = 'admin';
    case Owner = 'owner';
    case Observer = 'observer';
}
