<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

enum ConstitutionalSeverity: string
{
    case DETERMINATIVE = 'determinative';
    case BINDING = 'binding';
    case PERSUASIVE = 'persuasive';
}
