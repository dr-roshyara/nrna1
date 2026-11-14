<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

/** G-6c-4: the outcome of a confirmation request. */
enum ConfirmationStatus: string
{
    case Confirmed = 'confirmed';
    case Refused = 'refused';
}
