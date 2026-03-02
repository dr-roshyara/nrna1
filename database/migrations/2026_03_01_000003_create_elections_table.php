<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Type and status
            $table->enum('type', ['demo', 'real'])->default('demo');
            $table->enum('status', ['planned', 'active', 'completed', 'archived'])->default('planned');

            // Multi-tenancy - nullable for MODE 1 (public demo)
            $table->unsignedBigInteger('organisation_id')->nullable();

            // Timeline
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();

            // Configuration
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable();

            $table->timestamps();

            // Foreign key (allow NULL for demo MODE 1)
            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('cascade');

            // Indexes
            $table->index('type');
            $table->index('status');
            $table->index('is_active');
            $table->index(['organisation_id', 'status']);
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elections');
    }
};
