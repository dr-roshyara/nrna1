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
        Schema::create('member_directories', function (Blueprint $table) {
            $table->id();
            $table->uuid('member_id')->unique();
            $table->uuid('organisation_id');
            $table->uuid('organisation_user_id')->unique();
            $table->string('display_name');
            $table->string('email');
            $table->string('status')->default('ACTIVE');
            $table->uuid('membership_type_id')->nullable();
            $table->string('membership_type_name')->nullable();
            $table->timestamps();

            $table->index(['organisation_id', 'status']);
            $table->index(['organisation_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_directories');
    }
};
