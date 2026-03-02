<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidacies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id'); // The person running for office

            // Candidate ordering (for UI display)
            $table->unsignedInteger('position_order')->default(0);

            // Metadata
            $table->text('bio')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('political_party')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();

            // Foreign keys
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
                  ->onDelete('restrict');

            // Indexes
            $table->index(['election_id', 'post_id']);
            $table->index(['post_id', 'position_order']);
            $table->unique(['post_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidacies');
    }
};
