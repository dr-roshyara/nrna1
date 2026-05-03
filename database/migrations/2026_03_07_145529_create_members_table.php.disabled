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
        Schema::create('members', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->uuid('organisation_user_id')->unique();
            $table->string('membership_number')->nullable()->unique();
            $table->enum('status', ['active', 'expired', 'suspended'])->default('active');
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('membership_expires_at')->nullable();
            $table->timestamp('last_renewed_at')->nullable();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('cascade');
            $table->foreign('organisation_user_id')->references('id')->on('organisation_users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            $table->index(['organisation_id', 'status']);
            $table->index('membership_number');
            $table->index('membership_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
