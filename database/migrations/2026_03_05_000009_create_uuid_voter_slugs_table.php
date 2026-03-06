<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('voter_slugs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->uuid('election_id');
            $table->uuid('user_id');
            $table->string('slug')->unique();
            $table->integer('current_step')->default(1);
            $table->enum('status', ['active', 'voted', 'abstained'])->default('active');
            $table->json('step_meta')->nullable();
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

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->index(['organisation_id', 'election_id', 'user_id']);
            $table->unique(['election_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voter_slugs');
    }
};
