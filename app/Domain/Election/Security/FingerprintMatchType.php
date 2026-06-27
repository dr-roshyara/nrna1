<?php

namespace App\Domain\Election\Security;

enum FingerprintMatchType: string
{
    case ExactMatch = 'exact';
    case NoMatch = 'no_match';
    case NotRequired = 'not_required';
}
