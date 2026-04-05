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
        Schema::table('organizations', function (Blueprint $table) {
            // Add new columns if they don't exist
            if (!Schema::hasColumn('organizations', 'email')) {
                $table->string('email')->nullable()->after('name');
            }
            if (!Schema::hasColumn('organizations', 'address')) {
                $table->json('address')->nullable()->after('email');
            }
            if (!Schema::hasColumn('organizations', 'representative')) {
                $table->json('representative')->nullable()->after('address');
            }
            if (!Schema::hasColumn('organizations', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('representative');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['created_by_foreign']);
            $table->dropColumn([
                'email',
                'address',
                'representative',
                'created_by',
            ]);
        });
    }
};
