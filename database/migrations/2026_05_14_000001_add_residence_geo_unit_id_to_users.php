<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('residence_geo_unit_id')
                  ->nullable()
                  ->after('state');
            $table->foreign('residence_geo_unit_id')
                  ->references('id')
                  ->on('geo_administrative_units')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['residence_geo_unit_id']);
            $table->dropColumn('residence_geo_unit_id');
        });
    }
};
