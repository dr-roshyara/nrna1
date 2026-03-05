<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('candidacies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->uuid('post_id');
            $table->uuid('user_id')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->integer('position_order')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('cascade');

            $table->foreign('post_id')
                  ->references('id')
                  ->on('posts')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            $table->index(['organisation_id', 'post_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidacies');
    }
};
