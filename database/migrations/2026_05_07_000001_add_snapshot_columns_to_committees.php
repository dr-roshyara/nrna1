<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('committees', function (Blueprint $table) {
            $table->string('structure_id', 26)->nullable()->after('type');
            $table->unsignedSmallInteger('level_index')->nullable()->after('structure_id');
            $table->string('level_name')->nullable()->after('level_index');
            $table->string('geo_policy', 20)->nullable()->after('level_name');
            $table->string('geo_scope', 50)->nullable()->after('geo_policy');
        });
    }

    public function down(): void
    {
        Schema::table('committees', function (Blueprint $table) {
            $table->dropColumn(['structure_id', 'level_index', 'level_name', 'geo_policy', 'geo_scope']);
        });
    }
};
