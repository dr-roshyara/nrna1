<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidacies', function (Blueprint $table) {
            // Primary Keys
            $table->id();
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id');

            // Candidate Information
            $table->unsignedInteger('position_order')->default(0); // Display order within post
            $table->text('bio')->nullable(); // Candidate biography
            $table->string('photo_path')->nullable(); // Path to candidate photo
            $table->string('political_party')->nullable(); // Political party affiliation

            // Metadata
            $table->json('metadata')->nullable(); // Additional candidate information

            $table->timestamps();

            // Foreign Keys
            $table->foreign('election_id')
                  ->references('id')
                  ->on('elections')
                  ->onDelete('cascade');

            $table->foreign('post_id')
                  ->references('id')
                  ->on('posts')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('restrict'); // Cannot delete user if they're a candidate

            // Indexes
            $table->index(['election_id', 'post_id']); // Queries by election and post
            $table->unique(['post_id', 'user_id']); // Each user can run for each post only once
            $table->index('position_order'); // For display ordering
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidacies');
    }
};
