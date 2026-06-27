<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            // ✅ Data integrity tracking columns
            $table->string('data_checksum', 64)->nullable()->after('receipt_hash');
            $table->timestamp('results_last_synced_at')->nullable()->after('data_checksum');
            $table->boolean('is_verified')->default(false)->after('results_last_synced_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropColumn(['data_checksum', 'results_last_synced_at', 'is_verified']);
        });
    }
};
