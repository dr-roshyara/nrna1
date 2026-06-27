<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('committees', function (Blueprint $table) {
            $table->string('region_code', 20)->nullable()->after('geo_reference');
            $table->char('country_code', 2)->nullable()->after('region_code');
        });
    }

    public function down(): void
    {
        Schema::table('committees', function (Blueprint $table) {
            $table->dropColumn(['region_code', 'country_code']);
        });
    }
};
