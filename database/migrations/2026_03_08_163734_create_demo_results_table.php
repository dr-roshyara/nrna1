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
        Schema::create('demo_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id')->nullable();
            $table->uuid('vote_id');
            $table->uuid('election_id');
            $table->uuid('candidacy_id');  // FK to demo_candidacies
            $table->uuid('post_id');
            $table->integer('position_order')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add indexes for query performance
            $table->index('vote_id');
            $table->index('election_id');
            $table->index('candidacy_id');
            $table->index('post_id');
            $table->index('organisation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demo_results');
    }
};
