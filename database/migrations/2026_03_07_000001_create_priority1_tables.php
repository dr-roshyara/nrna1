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
        // Create messages table
        Schema::create('messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->text('message')->nullable();
            $table->string('code')->nullable();
            $table->string('message_receiver_id')->nullable();
            $table->string('message_receiver_name')->nullable();
            $table->string('message_sender_id')->nullable();
            $table->string('message_sender_name')->nullable();
            $table->timestamps();

            $table->foreign('organisation_id')
                ->references('id')
                ->on('organisations')
                ->onDelete('cascade');
            $table->index(['organisation_id']);
        });

        // Create images table
        Schema::create('images', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->timestamps();

            $table->foreign('organisation_id')
                ->references('id')
                ->on('organisations')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->index(['organisation_id']);
        });

        // Create uploads table
        Schema::create('uploads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->string('filename')->nullable();
            $table->timestamps();

            $table->foreign('organisation_id')
                ->references('id')
                ->on('organisations')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->index(['organisation_id']);
        });

        // Create calendars table
        Schema::create('calendars', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id')->nullable();
            $table->uuid('google_account_id')->nullable();
            $table->string('google_id')->nullable();
            $table->string('name')->nullable();
            $table->string('color')->nullable();
            $table->string('timezone')->nullable();
            $table->timestamps();

            $table->foreign('organisation_id')
                ->references('id')
                ->on('organisations')
                ->onDelete('cascade');
            $table->index(['organisation_id']);
        });

        // Create events table
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id')->nullable();
            $table->uuid('calendar_id')->nullable();
            $table->string('google_id')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('allday')->default(false);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();

            $table->foreign('organisation_id')
                ->references('id')
                ->on('organisations')
                ->onDelete('cascade');
            $table->foreign('calendar_id')
                ->references('id')
                ->on('calendars')
                ->onDelete('cascade');
            $table->index(['organisation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
        Schema::dropIfExists('calendars');
        Schema::dropIfExists('uploads');
        Schema::dropIfExists('images');
        Schema::dropIfExists('messages');
    }
};
