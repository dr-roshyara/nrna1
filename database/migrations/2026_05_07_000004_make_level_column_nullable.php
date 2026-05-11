<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('committees', function (Blueprint $table) {
            // Make the old `level` column nullable for backward compatibility during transition
            $table->integer('level')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('committees', function (Blueprint $table) {
            $table->integer('level')->change();
        });
    }
};
