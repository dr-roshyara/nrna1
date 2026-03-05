<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organisations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['platform', 'tenant'])->default('tenant');
            $table->boolean('is_default')->default(false);
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('representative')->nullable();
            $table->json('settings')->nullable();
            $table->json('languages')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organisations');
    }
};
