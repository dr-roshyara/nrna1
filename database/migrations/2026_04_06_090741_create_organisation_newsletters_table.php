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
        Schema::create('organisation_newsletters', function (Blueprint $table) {
            $table->id();
            $table->uuid('organisation_id');
            $table->uuid('created_by');
            $table->string('subject');
            $table->longText('html_content');
            $table->longText('plain_text')->nullable();
            $table->enum('status', ['draft','queued','processing','completed','failed','cancelled'])->default('draft');
            $table->unsignedInteger('total_recipients')->default(0);
            $table->unsignedInteger('sent_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->string('idempotency_key', 64)->nullable()->unique();
            $table->timestamp('queued_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('organisation_id')->references('id')->on('organisations')->cascadeOnDelete();
            $table->index(['organisation_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organisation_newsletters');
    }
};
