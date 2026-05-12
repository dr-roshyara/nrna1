<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Rule;

enum RuleCategory: string
{
    case DOMAIN_PURITY = 'domain_purity';
    case IMMUTABILITY = 'immutability';
    case DEPENDENCY = 'dependency';
    case CQRS = 'cqrs';
    case API_CONTRACT = 'api_contract';
}
