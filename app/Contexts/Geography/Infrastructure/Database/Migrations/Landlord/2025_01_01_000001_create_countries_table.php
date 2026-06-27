<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates the countries table in the landlord database.
     * Stores ISO country data and administrative hierarchy configuration.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            // ISO Standards
            $table->char('code', 2)->primary()->comment('ISO 3166-1 alpha-2: NP, IN, US');
            $table->char('code_alpha3', 3)->unique()->comment('ISO 3166-1 alpha-3: NPL, IND, USA');
            $table->char('code_numeric', 3)->unique()->comment('ISO 3166-1 numeric: 524, 356, 840');

            // Names (Multilingual)
            $table->string('name_en', 100)->index();
            $table->json('name_local')->comment('{"np": "नेपाल", "hi": "भारत", "es": "España"}');

            // Contact & Currency
            $table->string('phone_code', 10)->nullable()->comment('+977, +91, +1');
            $table->char('currency_code', 3)->nullable()->comment('NPR, INR, USD (ISO 4217)');
            $table->string('capital_en', 100)->nullable();

            // Administrative Hierarchy Configuration (CRITICAL)
            $table->json('admin_levels')->comment('
                Defines administrative levels for this country.
                Example for Nepal:
                {
                    "1": {"name": "Province", "local_name": "प्रदेश", "count": 7},
                    "2": {"name": "District", "local_name": "जिल्ला", "count": 77},
                    "3": {"name": "Local Level", "local_name": "स्थानीय तह", "count": 753},
                    "4": {"name": "Ward", "local_name": "वडा", "count": 6743}
                }
            ');

            // Validation Rules (Country-Specific)
            $table->json('id_validation_rules')->nullable()->comment('
                ID document validation rules per country.
                Example: {"citizenship": {"regex": "^[0-9]{1,2}-[0-9]{2}-[0-9]{6}$"}}
            ');

            $table->json('phone_validation_rules')->nullable()->comment('
                Phone number validation patterns.
                Example: {"regex": "^9[78][0-9]{8}$", "length": 10}
            ');

            // Platform Configuration
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_supported')->default(false)->index()->comment('Whether platform supports this country');

            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
