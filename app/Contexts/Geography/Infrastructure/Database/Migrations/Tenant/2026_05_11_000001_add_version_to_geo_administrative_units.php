<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds version field for optimistic locking (ADR-008, GEO-INV-06).
     * Mirrors Landlord schema change for tenant-specific geo units.
     */
    public function up(): void
    {
        Schema::table('geo_administrative_units', function (Blueprint $table) {
            $table->integer('version')->default(1)->comment('Optimistic locking version, increments on each write');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('geo_administrative_units', function (Blueprint $table) {
            $table->dropColumn('version');
        });
    }
};
