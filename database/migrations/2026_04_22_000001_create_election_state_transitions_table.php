<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('election_state_transitions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('election_id');
            $table->string('from_state', 50)->nullable(); // null = initial creation
            $table->string('to_state', 50);
            $table->string('trigger', 100); // 'manual'|'grace_period'|'time'|'force'
            $table->uuid('actor_id')->nullable();
            $table->text('reason')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('created_at'); // NO updated_at — immutable

            $table->foreign('election_id')->references('id')->on('elections')->cascadeOnDelete();
            $table->foreign('actor_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['election_id', 'created_at']);
            $table->index(['election_id', 'to_state']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('election_state_transitions');
    }
};
