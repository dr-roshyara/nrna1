<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee;

enum GovernanceTemplatePreset: string
{
    case NEPAL = 'nepal';
    case WORLDWIDE = 'worldwide';
    case GERMANY = 'germany';
    case FLAT = 'flat';

    public function getStructure(): array
    {
        return match($this) {
            self::NEPAL => [
                [
                    'index' => 1,
                    'code' => 'province',
                    'name' => 'Province',
                    'geo_policy' => 'required',
                    'geo_scope' => 'province',
                    'role_limits' => [],
                    'min_membership_years' => 0,
                ],
                [
                    'index' => 2,
                    'code' => 'district',
                    'name' => 'District',
                    'geo_policy' => 'required',
                    'geo_scope' => 'district',
                    'role_limits' => [],
                    'min_membership_years' => 0,
                ],
                [
                    'index' => 3,
                    'code' => 'municipality',
                    'name' => 'Municipality',
                    'geo_policy' => 'optional',
                    'geo_scope' => 'municipality',
                    'role_limits' => [],
                    'min_membership_years' => 0,
                ],
                [
                    'index' => 4,
                    'code' => 'ward',
                    'name' => 'Ward',
                    'geo_policy' => 'optional',
                    'geo_scope' => 'ward',
                    'role_limits' => [],
                    'min_membership_years' => 0,
                ],
            ],
            self::WORLDWIDE => [
                [
                    'index' => 1,
                    'code' => 'continent',
                    'name' => 'Continent',
                    'geo_policy' => 'none',
                    'geo_scope' => null,
                    'role_limits' => [],
                    'min_membership_years' => 0,
                ],
                [
                    'index' => 2,
                    'code' => 'region',
                    'name' => 'Region',
                    'geo_policy' => 'required',
                    'geo_scope' => 'region',
                    'role_limits' => [],
                    'min_membership_years' => 0,
                ],
                [
                    'index' => 3,
                    'code' => 'country',
                    'name' => 'Country',
                    'geo_policy' => 'required',
                    'geo_scope' => 'country',
                    'role_limits' => [],
                    'min_membership_years' => 0,
                ],
                [
                    'index' => 4,
                    'code' => 'state',
                    'name' => 'State/Province',
                    'geo_policy' => 'optional',
                    'geo_scope' => 'state',
                    'role_limits' => [],
                    'min_membership_years' => 0,
                ],
            ],
            self::GERMANY => [
                [
                    'index' => 1,
                    'code' => 'national',
                    'name' => 'National',
                    'geo_policy' => 'none',
                    'geo_scope' => null,
                    'role_limits' => [],
                    'min_membership_years' => 0,
                ],
                [
                    'index' => 2,
                    'code' => 'state',
                    'name' => 'State (Bundesland)',
                    'geo_policy' => 'required',
                    'geo_scope' => 'state',
                    'role_limits' => [],
                    'min_membership_years' => 0,
                ],
                [
                    'index' => 3,
                    'code' => 'district',
                    'name' => 'District (Landkreis)',
                    'geo_policy' => 'optional',
                    'geo_scope' => 'district',
                    'role_limits' => [],
                    'min_membership_years' => 0,
                ],
            ],
            self::FLAT => [
                [
                    'index' => 1,
                    'code' => 'general',
                    'name' => 'General Committee',
                    'geo_policy' => 'none',
                    'geo_scope' => null,
                    'role_limits' => [],
                    'min_membership_years' => 0,
                ],
            ],
        };
    }

    public function getDisplayName(): string
    {
        return match($this) {
            self::NEPAL => 'Nepal Structure',
            self::WORLDWIDE => 'Worldwide Structure',
            self::GERMANY => 'Germany Structure',
            self::FLAT => 'Flat Structure',
        };
    }

    public function getDescription(): string
    {
        return match($this) {
            self::NEPAL => 'Province → District → Municipality → Ward hierarchy',
            self::WORLDWIDE => 'Continent → Region → Country → State/Province hierarchy',
            self::GERMANY => 'National → State → District hierarchy',
            self::FLAT => 'Single general committee',
        };
    }
}
