<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organisations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type')->default('other'); // other, company, non-profit, political, educational
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('representative')->nullable();
            $table->json('settings')->nullable();
            $table->json('languages')->nullable();
            $table->boolean('is_platform')->default(false);
            $table->timestamps();

            $table->index('slug');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organisations');
    }
};
