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
        Schema::table('demo_candidacies', function (Blueprint $table) {
            // Change proposer_id and supporter_id from unsignedBigInteger to string
            $table->string('proposer_id')->nullable()->change();
            $table->string('supporter_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demo_candidacies', function (Blueprint $table) {
            // Revert back to unsignedBigInteger
            $table->unsignedBigInteger('proposer_id')->nullable()->change();
            $table->unsignedBigInteger('supporter_id')->nullable()->change();
        });
    }
};
