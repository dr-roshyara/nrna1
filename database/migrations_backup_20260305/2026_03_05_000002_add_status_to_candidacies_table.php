<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidacies', function (Blueprint $table) {
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('candidacies', 'status')) {
                $table->string('status')->default('pending')->after('position_order');
                $table->index('status'); // Add index for faster queries on status
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidacies', function (Blueprint $table) {
            // Only drop the column if it exists
            if (Schema::hasColumn('candidacies', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};