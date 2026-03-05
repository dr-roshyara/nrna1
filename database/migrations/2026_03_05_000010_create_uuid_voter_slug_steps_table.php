<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('voter_slug_steps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->uuid('voter_slug_id');
            $table->integer('step');
            $table->string('ip_address')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('cascade');

            $table->foreign('voter_slug_id')
                  ->references('id')
                  ->on('voter_slugs')
                  ->onDelete('cascade');

            $table->index(['organisation_id', 'voter_slug_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voter_slug_steps');
    }
};
