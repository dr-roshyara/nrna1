<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates the elections table to support both demo and real elections.
     * This keeps the election configuration independent from voter registration state.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elections', function (Blueprint $table) {
            $table->id();

            // Basic election information
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Election type: demo for testing, real for actual voting
            $table->enum('type', ['demo', 'real'])->default('demo');

            // Timeline
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            // Configuration
            $table->json('settings')->nullable()->comment('Election-specific settings as JSON');

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index('type');
            $table->index('is_active');
            $table->index(['type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('elections');
    }
}
