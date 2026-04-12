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
        Schema::table('demo_codes', function (Blueprint $table) {
            // Add new semantic columns only (no old column checks, no dropping)
            $table->string('code_to_open_voting_form')->nullable();
            $table->string('code_to_save_vote')->nullable();
            $table->boolean('is_code_to_open_voting_form_usable')->default(false);
            $table->boolean('is_code_to_save_vote_usable')->default(false);
            $table->dateTime('code_to_open_voting_form_sent_at')->nullable();
            $table->dateTime('code_to_save_vote_sent_at')->nullable();
            $table->dateTime('code_to_open_voting_form_used_at')->nullable();
            $table->dateTime('code_to_save_vote_used_at')->nullable();
            $table->uuid('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demo_codes', function (Blueprint $table) {
            $table->dropColumn([
                'code_to_open_voting_form',
                'code_to_save_vote',
                'is_code_to_open_voting_form_usable',
                'is_code_to_save_vote_usable',
                'code_to_open_voting_form_sent_at',
                'code_to_save_vote_sent_at',
                'code_to_open_voting_form_used_at',
                'code_to_save_vote_used_at',
                'user_id',
            ]);
        });
    }
};