<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('voter_registrations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->uuid('election_id');
            $table->uuid('user_id')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->timestamp('registered_at');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('cascade');

            $table->foreign('election_id')
                  ->references('id')
                  ->on('elections')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            $table->index(['organisation_id', 'election_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voter_registrations');
    }
};
