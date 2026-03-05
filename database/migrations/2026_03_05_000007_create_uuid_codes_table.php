<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('codes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->uuid('election_id');
            $table->string('code1');
            $table->string('code2')->nullable();
            $table->boolean('is_code1_usable')->default(true);
            $table->timestamp('code1_used_at')->nullable();
            $table->timestamp('code2_used_at')->nullable();
            $table->boolean('can_vote_now')->default(false);
            $table->boolean('has_voted')->default(false);
            $table->integer('voting_time_min')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('cascade');

            $table->foreign('election_id')
                  ->references('id')
                  ->on('elections')
                  ->onDelete('cascade');

            $table->index(['organisation_id', 'election_id']);
            $table->unique(['code1', 'organisation_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('codes');
    }
};
