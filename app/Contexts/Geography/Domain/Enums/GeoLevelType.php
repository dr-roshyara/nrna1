<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\Enums;

enum GeoLevelType: string
{
    case PROVINCE = 'province';
    case DISTRICT = 'district';
    case MUNICIPALITY = 'municipality';
    case WARD = 'ward';
    case STATE = 'state';
    case COUNTY = 'county';
    case CITY = 'city';
}
