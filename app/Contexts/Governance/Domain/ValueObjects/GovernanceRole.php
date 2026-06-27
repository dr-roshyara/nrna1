<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\ValueObjects;

enum GovernanceRole: string
{
    case ICC_PRESIDENT = 'ICC_PRESIDENT';
    case CONTINENT_PRESIDENT = 'CONTINENT_PRESIDENT';
    case COUNTRY_PRESIDENT = 'COUNTRY_PRESIDENT';
    case CHAPTER_PRESIDENT = 'CHAPTER_PRESIDENT';

    public function level(): int
    {
        return match($this) {
            self::ICC_PRESIDENT => 4,
            self::CONTINENT_PRESIDENT => 3,
            self::COUNTRY_PRESIDENT => 2,
            self::CHAPTER_PRESIDENT => 1,
        };
    }
}
