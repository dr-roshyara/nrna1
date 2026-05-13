<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->unsignedBigInteger('residence_geo_unit_id')
                ->nullable()
                ->after('membership_type_id');

            $table->foreign('residence_geo_unit_id')
                ->references('id')
                ->on('geo_administrative_units')
                ->onDelete('set null');

            $table->index('residence_geo_unit_id', 'idx_members_residence_geo_unit');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropIndex('idx_members_residence_geo_unit');
            $table->dropForeign(['residence_geo_unit_id']);
            $table->dropColumn('residence_geo_unit_id');
        });
    }
};
