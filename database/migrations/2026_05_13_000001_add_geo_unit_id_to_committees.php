<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('committees', function (Blueprint $table) {
            $table->unsignedBigInteger('geo_unit_id')->nullable()->after('country_code');

            $table->foreign('geo_unit_id')
                ->references('id')
                ->on('geo_administrative_units')
                ->onDelete('set null');

            $table->index(['organisation_id', 'geo_unit_id'], 'idx_committees_org_geo_unit');
        });
    }

    public function down(): void
    {
        Schema::table('committees', function (Blueprint $table) {
            $table->dropIndex('idx_committees_org_geo_unit');
            $table->dropForeign(['geo_unit_id']);
            $table->dropColumn('geo_unit_id');
        });
    }
};
