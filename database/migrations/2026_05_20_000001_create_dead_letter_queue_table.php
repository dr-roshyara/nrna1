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
        Schema::create('dead_letter_queue', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('queue_name');
            $table->json('payload');
            $table->text('error_message');
            $table->string('error_class');
            $table->uuid('organisation_id')->nullable();
            $table->uuid('election_id')->nullable();
            $table->timestamp('failed_at');
            $table->timestamp('retried_at')->nullable();
            $table->timestamps();

            $table->index(['queue_name', 'failed_at']);
            $table->index('organisation_id');
            $table->index('election_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dead_letter_queue');
    }
};
