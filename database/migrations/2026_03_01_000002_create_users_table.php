<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->rememberToken();
            // user_ip 
            $table->string('user_ip')->nullable();            
            // Multi-tenancy
            $table->unsignedBigInteger('organisation_id')->default(1);

            // Voting
            $table->boolean('is_voter')->default(false);
            $table->boolean('can_vote')->default(false);
            // $table->boolean('can_vote_now')->default(false);           
            $table->boolean('has_voted')->default(false);
            $table->string('voting_ip')->nullable();

            $table->string('region')->nullable(); // For regional post filtering

            // Metadata
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('restrict');

            // Indexes
            $table->index(['organisation_id', 'email']);
            $table->index('voting_ip');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
