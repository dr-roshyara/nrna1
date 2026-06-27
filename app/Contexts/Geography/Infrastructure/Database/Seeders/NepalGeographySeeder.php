<?php

namespace App\Contexts\Geography\Infrastructure\Database\Seeders;

use App\Contexts\Geography\Domain\Models\GeoAdministrativeUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Nepal Geography Seeder
 *
 * Seeds Nepal's complete administrative hierarchy into the polymorphic
 * geo_administrative_units table.
 *
 * Structure:
 * - 7 Provinces (Level 1)
 * - 77 Districts (Level 2)
 * - 753 Local Levels (Level 3)
 * - ~6,743 Wards (Level 4)
 * - Villages/Toles/Areas (Level 5) - Official administrative units
 *
 * Note: This seeder includes all 7 provinces and a representative sample.
 * For production, complete data for all 77 districts, 753 local levels,
 * 6,743 wards, and villages/toles should be added.
 *
 * CRITICAL: Levels 1-5 are OFFICIAL geography (mirrored to tenants)
 *           Levels 6-8 are CUSTOM party units (created by tenants)
 */
class NepalGeographySeeder extends Seeder
{
    private const COUNTRY_CODE = 'NP';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $this->command->info('Seeding Nepal geography...');

            // Seed all 7 provinces
            $provinces = $this->seedProvinces();
            $this->command->info('✓ Seeded 7 provinces');

            // Seed districts for each province
            $districts = $this->seedDistricts($provinces);
            $this->command->info('✓ Seeded districts');

            // Seed local levels
            $localLevels = $this->seedLocalLevels($districts);
            $this->command->info('✓ Seeded local levels');

            // Seed wards
            $wards = $this->seedWards($localLevels);
            $this->command->info('✓ Seeded wards');

            // Seed villages/toles/areas (Level 5 - OFFICIAL)
            $this->seedVillagesToles($wards);
            $this->command->info('✓ Seeded villages/toles/areas');

            $this->command->info('Nepal geography seeded successfully!');
        });
    }

    /**
     * Seed all 7 provinces of Nepal.
     *
     * @return array Province IDs keyed by code
     */
    private function seedProvinces(): array
    {
        $provincesData = [
            [
                'code' => 'NP-P1',
                'name_local' => ['en' => 'Koshi Province', 'np' => 'कोशी प्रदेश'],
                'metadata' => ['capital' => 'Biratnagar', 'total_districts' => 14],
            ],
            [
                'code' => 'NP-P2',
                'name_local' => ['en' => 'Madhesh Province', 'np' => 'मधेश प्रदेश'],
                'metadata' => ['capital' => 'Janakpur', 'total_districts' => 8],
            ],
            [
                'code' => 'NP-P3',
                'name_local' => ['en' => 'Bagmati Province', 'np' => 'बागमती प्रदेश'],
                'metadata' => ['capital' => 'Hetauda', 'total_districts' => 13],
            ],
            [
                'code' => 'NP-P4',
                'name_local' => ['en' => 'Gandaki Province', 'np' => 'गण्डकी प्रदेश'],
                'metadata' => ['capital' => 'Pokhara', 'total_districts' => 11],
            ],
            [
                'code' => 'NP-P5',
                'name_local' => ['en' => 'Lumbini Province', 'np' => 'लुम्बिनी प्रदेश'],
                'metadata' => ['capital' => 'Deukhuri', 'total_districts' => 12],
            ],
            [
                'code' => 'NP-P6',
                'name_local' => ['en' => 'Karnali Province', 'np' => 'कर्णाली प्रदेश'],
                'metadata' => ['capital' => 'Birendranagar', 'total_districts' => 10],
            ],
            [
                'code' => 'NP-P7',
                'name_local' => ['en' => 'Sudurpashchim Province', 'np' => 'सुदूरपश्चिम प्रदेश'],
                'metadata' => ['capital' => 'Godawari', 'total_districts' => 9],
            ],
        ];

        $provinces = [];

        foreach ($provincesData as $data) {
            $province = GeoAdministrativeUnit::create([
                'country_code' => self::COUNTRY_CODE,
                'admin_level' => 1,
                'admin_type' => 'province',
                'parent_id' => null,
                'code' => $data['code'],
                'name_local' => $data['name_local'],
                'metadata' => $data['metadata'],
                'is_active' => true,
            ]);

            $provinces[$data['code']] = $province->id;
        }

        return $provinces;
    }

    /**
     * Seed districts for all provinces.
     *
     * @param array $provinces Province IDs keyed by code
     * @return array District IDs
     */
    private function seedDistricts(array $provinces): array
    {
        // Sample districts for demonstration
        // For production, all 77 districts should be added
        $districtsData = [
            // Koshi Province (NP-P1) - Sample districts
            [
                'province_code' => 'NP-P1',
                'code' => 'NP-DIST-01',
                'local_code' => 'CBS-01',
                'name_local' => ['en' => 'Bhojpur', 'np' => 'भोजपुर'],
                'metadata' => ['headquarter' => 'Bhojpur'],
            ],
            [
                'province_code' => 'NP-P1',
                'code' => 'NP-DIST-02',
                'local_code' => 'CBS-02',
                'name_local' => ['en' => 'Dhankuta', 'np' => 'धनकुटा'],
                'metadata' => ['headquarter' => 'Dhankuta'],
            ],
            [
                'province_code' => 'NP-P1',
                'code' => 'NP-DIST-03',
                'local_code' => 'CBS-03',
                'name_local' => ['en' => 'Ilam', 'np' => 'इलाम'],
                'metadata' => ['headquarter' => 'Ilam'],
            ],
            [
                'province_code' => 'NP-P1',
                'code' => 'NP-DIST-04',
                'local_code' => 'CBS-04',
                'name_local' => ['en' => 'Jhapa', 'np' => 'झापा'],
                'metadata' => ['headquarter' => 'Chandragadhi'],
            ],
            [
                'province_code' => 'NP-P1',
                'code' => 'NP-DIST-05',
                'local_code' => 'CBS-05',
                'name_local' => ['en' => 'Morang', 'np' => 'मोरङ'],
                'metadata' => ['headquarter' => 'Biratnagar'],
            ],

            // Bagmati Province (NP-P3) - Sample districts including Kathmandu
            [
                'province_code' => 'NP-P3',
                'code' => 'NP-DIST-25',
                'local_code' => 'CBS-25',
                'name_local' => ['en' => 'Kathmandu', 'np' => 'काठमाडौं'],
                'metadata' => ['headquarter' => 'Kathmandu', 'is_capital_district' => true],
            ],
            [
                'province_code' => 'NP-P3',
                'code' => 'NP-DIST-26',
                'local_code' => 'CBS-26',
                'name_local' => ['en' => 'Lalitpur', 'np' => 'ललितपुर'],
                'metadata' => ['headquarter' => 'Lalitpur'],
            ],
            [
                'province_code' => 'NP-P3',
                'code' => 'NP-DIST-27',
                'local_code' => 'CBS-27',
                'name_local' => ['en' => 'Bhaktapur', 'np' => 'भक्तपुर'],
                'metadata' => ['headquarter' => 'Bhaktapur'],
            ],

            // TODO: Add remaining 69 districts for other provinces
        ];

        $districts = [];

        foreach ($districtsData as $data) {
            $district = GeoAdministrativeUnit::create([
                'country_code' => self::COUNTRY_CODE,
                'admin_level' => 2,
                'admin_type' => 'district',
                'parent_id' => $provinces[$data['province_code']],
                'code' => $data['code'],
                'local_code' => $data['local_code'],
                'name_local' => $data['name_local'],
                'metadata' => $data['metadata'],
                'is_active' => true,
            ]);

            $districts[$data['code']] = $district->id;
        }

        return $districts;
    }

    /**
     * Seed local levels (municipalities and rural municipalities).
     *
     * @param array $districts District IDs
     * @return array Local level IDs
     */
    private function seedLocalLevels(array $districts): array
    {
        // Sample local levels for demonstration
        // For production, all 753 local levels should be added
        $localLevelsData = [
            // Dhankuta District
            [
                'district_code' => 'NP-DIST-02',
                'code' => 'NP-LL-001',
                'name_local' => ['en' => 'Dhankuta Municipality', 'np' => 'धनकुटा नगरपालिका'],
                'type' => 'Municipality',
                'metadata' => ['total_wards' => 10],
            ],
            [
                'district_code' => 'NP-DIST-02',
                'code' => 'NP-LL-002',
                'name_local' => ['en' => 'Pakhribas Municipality', 'np' => 'पाख्रिबास नगरपालिका'],
                'type' => 'Municipality',
                'metadata' => ['total_wards' => 8],
            ],
            [
                'district_code' => 'NP-DIST-02',
                'code' => 'NP-LL-003',
                'name_local' => ['en' => 'Mahalaxmi Municipality', 'np' => 'महालक्ष्मी नगरपालिका'],
                'type' => 'Municipality',
                'metadata' => ['total_wards' => 10],
            ],

            // Kathmandu District
            [
                'district_code' => 'NP-DIST-25',
                'code' => 'NP-LL-100',
                'name_local' => ['en' => 'Kathmandu Metropolitan City', 'np' => 'काठमाडौं महानगरपालिका'],
                'type' => 'Metropolitan City',
                'metadata' => ['total_wards' => 32],
            ],
            [
                'district_code' => 'NP-DIST-25',
                'code' => 'NP-LL-101',
                'name_local' => ['en' => 'Kageshwori Manahara Municipality', 'np' => 'कागेश्वरी मनोहरा नगरपालिका'],
                'type' => 'Municipality',
                'metadata' => ['total_wards' => 9],
            ],

            // TODO: Add remaining ~748 local levels
        ];

        $localLevels = [];

        foreach ($localLevelsData as $data) {
            $localLevel = GeoAdministrativeUnit::create([
                'country_code' => self::COUNTRY_CODE,
                'admin_level' => 3,
                'admin_type' => 'local_level',
                'parent_id' => $districts[$data['district_code']],
                'code' => $data['code'],
                'name_local' => $data['name_local'],
                'metadata' => array_merge($data['metadata'], ['type' => $data['type']]),
                'is_active' => true,
            ]);

            $localLevels[$data['code']] = $localLevel->id;
        }

        return $localLevels;
    }

    /**
     * Seed wards for local levels.
     *
     * @param array $localLevels Local level IDs
     * @return array Ward IDs keyed by code
     */
    private function seedWards(array $localLevels): array
    {
        // Sample wards for demonstration
        // For production, all ~6,743 wards should be added

        $wards = [];

        // Dhankuta Municipality (10 wards)
        if (isset($localLevels['NP-LL-001'])) {
            for ($i = 1; $i <= 10; $i++) {
                $ward = GeoAdministrativeUnit::create([
                    'country_code' => self::COUNTRY_CODE,
                    'admin_level' => 4,
                    'admin_type' => 'ward',
                    'parent_id' => $localLevels['NP-LL-001'],
                    'code' => "NP-LL-001-W{$i}",
                    'name_local' => [
                        'en' => "Dhankuta Municipality Ward {$i}",
                        'np' => "धनकुटा नगरपालिका वडा {$i}",
                    ],
                    'metadata' => ['ward_number' => $i],
                    'is_active' => true,
                ]);

                $wards["NP-LL-001-W{$i}"] = $ward->id;
            }
        }

        // Kathmandu Metropolitan City (32 wards)
        if (isset($localLevels['NP-LL-100'])) {
            for ($i = 1; $i <= 32; $i++) {
                $ward = GeoAdministrativeUnit::create([
                    'country_code' => self::COUNTRY_CODE,
                    'admin_level' => 4,
                    'admin_type' => 'ward',
                    'parent_id' => $localLevels['NP-LL-100'],
                    'code' => "NP-LL-100-W{$i}",
                    'name_local' => [
                        'en' => "Kathmandu Metropolitan City Ward {$i}",
                        'np' => "काठमाडौं महानगरपालिका वडा {$i}",
                    ],
                    'metadata' => ['ward_number' => $i],
                    'is_active' => true,
                ]);

                $wards["NP-LL-100-W{$i}"] = $ward->id;
            }
        }

        // TODO: Add remaining ~6,691 wards for other local levels
        // This can be done by:
        // 1. Importing from CSV file
        // 2. Fetching from government API
        // 3. Manual data entry

        return $wards;
    }

    /**
     * Seed villages/toles/areas (Level 5 - OFFICIAL).
     *
     * Level 5 represents the smallest official administrative units in Nepal.
     * These are OFFICIAL geography units, not party-specific customizations.
     *
     * @param array $wards Ward IDs keyed by code
     * @return void
     */
    private function seedVillagesToles(array $wards): void
    {
        // Sample villages/toles for demonstration
        // For production, complete village/tole data should be added

        $villagesTolesData = [
            // Dhankuta Municipality Ward 1 - Sample villages/toles
            [
                'ward_code' => 'NP-LL-001-W1',
                'code' => 'NP-VT-001',
                'name_local' => ['en' => 'Bhanu Chowk Area', 'np' => 'भानु चोक क्षेत्र'],
                'metadata' => ['area_type' => 'commercial'],
            ],
            [
                'ward_code' => 'NP-LL-001-W1',
                'code' => 'NP-VT-002',
                'name_local' => ['en' => 'Hile Bazaar', 'np' => 'हिले बजार'],
                'metadata' => ['area_type' => 'commercial'],
            ],
            [
                'ward_code' => 'NP-LL-001-W2',
                'code' => 'NP-VT-003',
                'name_local' => ['en' => 'Pokhari Tole', 'np' => 'पोखरी टोल'],
                'metadata' => ['area_type' => 'residential'],
            ],

            // Kathmandu Metropolitan City Ward 1 - Sample villages/toles
            [
                'ward_code' => 'NP-LL-100-W1',
                'code' => 'NP-VT-100',
                'name_local' => ['en' => 'Tripureshwor Area', 'np' => 'त्रिपुरेश्वर क्षेत्र'],
                'metadata' => ['area_type' => 'mixed'],
            ],
            [
                'ward_code' => 'NP-LL-100-W1',
                'code' => 'NP-VT-101',
                'name_local' => ['en' => 'Kalimati Tole', 'np' => 'कालिमाटी टोल'],
                'metadata' => ['area_type' => 'residential'],
            ],

            // Kathmandu Metropolitan City Ward 16 - Thamel area
            [
                'ward_code' => 'NP-LL-100-W16',
                'code' => 'NP-VT-200',
                'name_local' => ['en' => 'Thamel', 'np' => 'थमेल'],
                'metadata' => ['area_type' => 'tourist', 'famous' => true],
            ],
            [
                'ward_code' => 'NP-LL-100-W16',
                'code' => 'NP-VT-201',
                'name_local' => ['en' => 'Jyatha Tole', 'np' => 'ज्याठा टोल'],
                'metadata' => ['area_type' => 'commercial'],
            ],

            // Kathmandu Metropolitan City Ward 32 - Sample villages/toles
            [
                'ward_code' => 'NP-LL-100-W32',
                'code' => 'NP-VT-300',
                'name_local' => ['en' => 'Bhimdhunga Area', 'np' => 'भीमधुंगा क्षेत्र'],
                'metadata' => ['area_type' => 'residential'],
            ],
            [
                'ward_code' => 'NP-LL-100-W32',
                'code' => 'NP-VT-301',
                'name_local' => ['en' => 'Dakshinkali Area', 'np' => 'दक्षिणकाली क्षेत्र'],
                'metadata' => ['area_type' => 'religious'],
            ],

            // TODO: Add remaining villages/toles for all wards
            // Each ward typically has 2-5 villages/toles depending on urbanization
        ];

        foreach ($villagesTolesData as $data) {
            // Only create if parent ward exists
            if (isset($wards[$data['ward_code']])) {
                GeoAdministrativeUnit::create([
                    'country_code' => self::COUNTRY_CODE,
                    'admin_level' => 5,
                    'admin_type' => 'village_tole',
                    'parent_id' => $wards[$data['ward_code']],
                    'code' => $data['code'],
                    'name_local' => $data['name_local'],
                    'metadata' => $data['metadata'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
