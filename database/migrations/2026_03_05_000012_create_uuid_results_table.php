<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->uuid('vote_id');
            $table->uuid('election_id');
            $table->uuid('candidacy_id');
            $table->uuid('post_id');
            // NO user_id - results are anonymous
            $table->integer('position_order')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('cascade');

            $table->foreign('vote_id')
                  ->references('id')
                  ->on('votes')
                  ->onDelete('cascade');

            $table->foreign('election_id')
                  ->references('id')
                  ->on('elections')
                  ->onDelete('cascade');

            $table->foreign('candidacy_id')
                  ->references('id')
                  ->on('candidacies')
                  ->onDelete('cascade');

            $table->foreign('post_id')
                  ->references('id')
                  ->on('posts')
                  ->onDelete('cascade');

            $table->index(['organisation_id', 'election_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
