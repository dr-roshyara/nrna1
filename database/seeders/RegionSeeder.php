<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            ['code' => 'europe', 'name' => 'Europe', 'countries' => ['DE', 'FR', 'IT', 'ES', 'GB', 'NL', 'BE', 'AT', 'CH', 'SE', 'NO', 'DK', 'FI', 'PL', 'CZ', 'HU', 'RO', 'GR', 'PT', 'IE']],
            ['code' => 'asia', 'name' => 'Asia', 'countries' => ['NP', 'IN', 'PK', 'BD', 'LK', 'CN', 'JP', 'KR', 'TH', 'VN', 'PH', 'ID', 'MY', 'SG', 'BT', 'MM', 'KH', 'LA']],
            ['code' => 'americas', 'name' => 'Americas', 'countries' => ['US', 'CA', 'MX', 'BR', 'AR', 'CL', 'CO', 'PE', 'VE', 'EC', 'CU', 'HT', 'DO', 'JM']],
            ['code' => 'africa', 'name' => 'Africa', 'countries' => ['ZA', 'EG', 'NG', 'KE', 'ET', 'GH', 'MA', 'TZ', 'UG', 'ZM', 'MW', 'BW', 'NA', 'MZ', 'SZ', 'LS', 'MU', 'SC']],
            ['code' => 'oceania', 'name' => 'Oceania', 'countries' => ['AU', 'NZ', 'FJ', 'PG', 'SB', 'VU', 'WS', 'TO', 'KI', 'MH', 'FM', 'PW']],
        ];

        // Get all existing country codes to guard FK constraint
        $existingCountries = DB::table('countries')->pluck('code')->map(fn($c) => strtoupper($c))->toArray();

        foreach ($regions as $region) {
            $regionId = DB::table('regions')->insertGetId([
                'code' => $region['code'],
                'name' => $region['name'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert only countries that exist in database
            foreach ($region['countries'] as $countryCode) {
                if (in_array(strtoupper($countryCode), $existingCountries)) {
                    DB::table('region_country')->insert([
                        'region_id' => $regionId,
                        'country_code' => strtoupper($countryCode),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
