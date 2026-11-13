<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

enum RosterRefusal: string
{
    case AlreadyAppointed = 'already-appointed';
    case NotAppointed = 'not-appointed';
    case UnknownVersion = 'unknown-version';
}
