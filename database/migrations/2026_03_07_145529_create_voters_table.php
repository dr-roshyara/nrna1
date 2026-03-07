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
        Schema::create('voters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->uuid('member_id');
            $table->uuid('election_id');
            $table->enum('status', ['eligible', 'voted', 'ineligible'])->default('eligible');
            $table->text('ineligibility_reason')->nullable();
            $table->boolean('has_voted')->default(false);
            $table->timestamp('voted_at')->nullable();
            $table->string('voter_number')->nullable();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            $table->unique(['member_id', 'election_id']);
            $table->index(['organisation_id', 'status', 'election_id']);
            $table->index(['election_id', 'status']);
            $table->index('voted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voters');
    }
};
