<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('post_id')->unique(); // Unique identifier for the post
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('organisation_id');

            $table->string('name');
            $table->string('nepali_name')->nullable(); // Nepali translation of post name
            $table->text('description')->nullable();

            // Visibility and hierarchy
            $table->boolean('is_national_wide')->default(true);
            $table->string('state_name')->nullable(); // For regional posts (e.g., Bayern, Baden, NRW)

            // Selection rules
            $table->unsignedInteger('required_number')->default(1); // How many candidates must be selected
            $table->boolean('select_all_required')->default(true); // Must select exactly N or up to N

            // Ordering
            $table->unsignedInteger('position_order')->default(0);

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('election_id')
                  ->references('id')
                  ->on('elections')
                  ->onDelete('cascade');

            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('cascade');
            
            // Indexes
            $table->index(['election_id', 'is_national_wide']);
            $table->index(['election_id', 'state_name']);
            $table->index(['organisation_id', 'election_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
