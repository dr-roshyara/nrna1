<?php

namespace App\Contexts\Geography\Infrastructure\Database\Seeders;

use App\Contexts\Geography\Domain\Models\Country;
use Illuminate\Database\Seeder;

/**
 * Countries Seeder
 *
 * Seeds the countries table with ISO 3166-1 data.
 * Includes administrative hierarchy configuration for each country.
 */
class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = $this->getCountriesData();

        foreach ($countries as $countryData) {
            Country::updateOrCreate(
                ['code' => $countryData['code']],
                $countryData
            );
        }

        $this->command->info('Countries seeded successfully!');
    }

    /**
     * Get countries data.
     *
     * @return array
     */
    private function getCountriesData(): array
    {
        return [
            // Nepal - FULLY SUPPORTED
            [
                'code' => 'NP',
                'code_alpha3' => 'NPL',
                'code_numeric' => '524',
                'name_en' => 'Nepal',
                'name_local' => [
                    'np' => 'नेपाल',
                    'en' => 'Nepal',
                ],
                'phone_code' => '+977',
                'currency_code' => 'NPR',
                'capital_en' => 'Kathmandu',
                'admin_levels' => [
                    '1' => [
                        'name' => 'Province',
                        'local_name' => 'प्रदेश',
                        'count' => 7,
                    ],
                    '2' => [
                        'name' => 'District',
                        'local_name' => 'जिल्ला',
                        'count' => 77,
                    ],
                    '3' => [
                        'name' => 'Local Level',
                        'local_name' => 'स्थानीय तह',
                        'count' => 753,
                    ],
                    '4' => [
                        'name' => 'Ward',
                        'local_name' => 'वडा',
                        'count' => 6743,
                    ],
                ],
                'id_validation_rules' => [
                    'citizenship' => [
                        'regex' => '^[0-9]{1,2}-[0-9]{2}-[0-9]{6}$',
                        'example' => '01-01-123456',
                    ],
                    'passport' => [
                        'regex' => '^[0-9]{8}$',
                        'example' => '12345678',
                    ],
                ],
                'phone_validation_rules' => [
                    'regex' => '^9[78][0-9]{8}$',
                    'length' => 10,
                    'example' => '9841234567',
                ],
                'is_active' => true,
                'is_supported' => true,
            ],

            // India - FUTURE SUPPORT
            [
                'code' => 'IN',
                'code_alpha3' => 'IND',
                'code_numeric' => '356',
                'name_en' => 'India',
                'name_local' => [
                    'hi' => 'भारत',
                    'en' => 'India',
                ],
                'phone_code' => '+91',
                'currency_code' => 'INR',
                'capital_en' => 'New Delhi',
                'admin_levels' => [
                    '1' => [
                        'name' => 'State',
                        'local_name' => 'राज्य',
                        'count' => 28,
                    ],
                    '2' => [
                        'name' => 'District',
                        'local_name' => 'जिला',
                        'count' => 766,
                    ],
                    '3' => [
                        'name' => 'Sub-district',
                        'local_name' => 'उप-जिला',
                        'count' => null, // Variable
                    ],
                    '4' => [
                        'name' => 'Block',
                        'local_name' => 'ब्लॉक',
                        'count' => null, // Variable
                    ],
                    '5' => [
                        'name' => 'Gram Panchayat',
                        'local_name' => 'ग्राम पंचायत',
                        'count' => null, // Variable
                    ],
                ],
                'id_validation_rules' => [
                    'aadhaar' => [
                        'regex' => '^[0-9]{12}$',
                        'example' => '123456789012',
                    ],
                    'pan' => [
                        'regex' => '^[A-Z]{5}[0-9]{4}[A-Z]{1}$',
                        'example' => 'ABCDE1234F',
                    ],
                ],
                'phone_validation_rules' => [
                    'regex' => '^[6-9][0-9]{9}$',
                    'length' => 10,
                    'example' => '9876543210',
                ],
                'is_active' => true,
                'is_supported' => false, // Not supported yet
            ],

            // Bangladesh - FUTURE SUPPORT
            [
                'code' => 'BD',
                'code_alpha3' => 'BGD',
                'code_numeric' => '050',
                'name_en' => 'Bangladesh',
                'name_local' => [
                    'bn' => 'বাংলাদেশ',
                    'en' => 'Bangladesh',
                ],
                'phone_code' => '+880',
                'currency_code' => 'BDT',
                'capital_en' => 'Dhaka',
                'admin_levels' => [
                    '1' => [
                        'name' => 'Division',
                        'local_name' => 'বিভাগ',
                        'count' => 8,
                    ],
                    '2' => [
                        'name' => 'District',
                        'local_name' => 'জেলা',
                        'count' => 64,
                    ],
                    '3' => [
                        'name' => 'Upazila',
                        'local_name' => 'উপজেলা',
                        'count' => 492,
                    ],
                    '4' => [
                        'name' => 'Union',
                        'local_name' => 'ইউনিয়ন',
                        'count' => 4571,
                    ],
                ],
                'id_validation_rules' => null,
                'phone_validation_rules' => [
                    'regex' => '^1[3-9][0-9]{8}$',
                    'length' => 11,
                    'example' => '17012345678',
                ],
                'is_active' => true,
                'is_supported' => false,
            ],

            // USA - FUTURE SUPPORT
            [
                'code' => 'US',
                'code_alpha3' => 'USA',
                'code_numeric' => '840',
                'name_en' => 'United States',
                'name_local' => [
                    'en' => 'United States',
                ],
                'phone_code' => '+1',
                'currency_code' => 'USD',
                'capital_en' => 'Washington, D.C.',
                'admin_levels' => [
                    '1' => [
                        'name' => 'State',
                        'local_name' => 'State',
                        'count' => 50,
                    ],
                    '2' => [
                        'name' => 'County',
                        'local_name' => 'County',
                        'count' => 3143,
                    ],
                    '3' => [
                        'name' => 'City/Township',
                        'local_name' => 'City/Township',
                        'count' => null, // Variable
                    ],
                ],
                'id_validation_rules' => [
                    'ssn' => [
                        'regex' => '^[0-9]{3}-[0-9]{2}-[0-9]{4}$',
                        'example' => '123-45-6789',
                    ],
                ],
                'phone_validation_rules' => [
                    'regex' => '^[2-9][0-9]{9}$',
                    'length' => 10,
                    'example' => '2025551234',
                ],
                'is_active' => true,
                'is_supported' => false,
            ],
        ];
    }
}
