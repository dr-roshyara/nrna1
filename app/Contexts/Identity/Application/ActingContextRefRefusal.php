<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application;

enum ActingContextRefRefusal: string
{
    case NotSignedIn = 'not-signed-in';
    case AccountNotActive = 'account-not-active';
    case IdentityUnavailable = 'identity-unavailable';
}
