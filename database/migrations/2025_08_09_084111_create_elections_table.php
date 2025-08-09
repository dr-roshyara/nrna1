<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamp('voting_start_time');
            $table->timestamp('voting_end_time');
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
            
            // Verification fields
            $table->boolean('results_verified')->default(false);
            $table->timestamp('results_verified_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            
            // Authorization fields
            $table->boolean('authorization_started')->default(false);
            $table->timestamp('authorization_started_at')->nullable();
            $table->string('authorization_session_id')->nullable();
            $table->timestamp('authorization_deadline')->nullable();
            $table->boolean('authorization_complete')->default(false);
            $table->timestamp('authorization_completed_at')->nullable();
            
            // Publication fields
            $table->boolean('results_published')->default(false);
            $table->timestamp('results_published_at')->nullable();
            $table->unsignedBigInteger('published_by')->nullable();
            $table->json('publication_summary')->nullable();
            
            $table->timestamps();
            
            // ✅ FIXED: Shorter index names (under 64 characters)
            $table->index(['status', 'voting_start_time', 'voting_end_time'], 'elections_status_voting_times_idx');
            $table->index(['results_verified', 'authorization_complete', 'results_published'], 'elections_publication_status_idx');
            $table->index('authorization_session_id', 'elections_auth_session_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('elections');
    }
}