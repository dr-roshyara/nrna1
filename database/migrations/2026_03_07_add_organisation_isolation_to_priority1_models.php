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
        // Add organisation_id to messages table
        if (Schema::hasTable('messages') && !Schema::hasColumn('messages', 'organisation_id')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->uuid('organisation_id')->after('id')->nullable();
                $table->foreign('organisation_id')
                    ->references('id')
                    ->on('organisations')
                    ->onDelete('cascade');
                $table->index(['organisation_id']);
            });
        }

        // Add organisation_id to images table
        if (Schema::hasTable('images') && !Schema::hasColumn('images', 'organisation_id')) {
            Schema::table('images', function (Blueprint $table) {
                $table->uuid('organisation_id')->after('id')->nullable();
                $table->foreign('organisation_id')
                    ->references('id')
                    ->on('organisations')
                    ->onDelete('cascade');
                $table->index(['organisation_id']);
            });
        }

        // Add organisation_id to uploads table
        if (Schema::hasTable('uploads') && !Schema::hasColumn('uploads', 'organisation_id')) {
            Schema::table('uploads', function (Blueprint $table) {
                $table->uuid('organisation_id')->after('id')->nullable();
                $table->foreign('organisation_id')
                    ->references('id')
                    ->on('organisations')
                    ->onDelete('cascade');
                $table->index(['organisation_id']);
            });
        }

        // Add organisation_id to calendars table
        if (Schema::hasTable('calendars') && !Schema::hasColumn('calendars', 'organisation_id')) {
            Schema::table('calendars', function (Blueprint $table) {
                $table->uuid('organisation_id')->after('id')->nullable();
                $table->foreign('organisation_id')
                    ->references('id')
                    ->on('organisations')
                    ->onDelete('cascade');
                $table->index(['organisation_id']);
            });
        }

        // Add organisation_id to events table
        if (Schema::hasTable('events') && !Schema::hasColumn('events', 'organisation_id')) {
            Schema::table('events', function (Blueprint $table) {
                $table->uuid('organisation_id')->after('id')->nullable();
                $table->foreign('organisation_id')
                    ->references('id')
                    ->on('organisations')
                    ->onDelete('cascade');
                $table->index(['organisation_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys and columns from messages
        if (Schema::hasTable('messages')) {
            Schema::table('messages', function (Blueprint $table) {
                if (Schema::hasColumn('messages', 'organisation_id')) {
                    $table->dropForeign(['organisation_id']);
                    $table->dropIndex(['organisation_id']);
                    $table->dropColumn('organisation_id');
                }
            });
        }

        // Drop foreign keys and columns from images
        if (Schema::hasTable('images')) {
            Schema::table('images', function (Blueprint $table) {
                if (Schema::hasColumn('images', 'organisation_id')) {
                    $table->dropForeign(['organisation_id']);
                    $table->dropIndex(['organisation_id']);
                    $table->dropColumn('organisation_id');
                }
            });
        }

        // Drop foreign keys and columns from uploads
        if (Schema::hasTable('uploads')) {
            Schema::table('uploads', function (Blueprint $table) {
                if (Schema::hasColumn('uploads', 'organisation_id')) {
                    $table->dropForeign(['organisation_id']);
                    $table->dropIndex(['organisation_id']);
                    $table->dropColumn('organisation_id');
                }
            });
        }

        // Drop foreign keys and columns from calendars
        if (Schema::hasTable('calendars')) {
            Schema::table('calendars', function (Blueprint $table) {
                if (Schema::hasColumn('calendars', 'organisation_id')) {
                    $table->dropForeign(['organisation_id']);
                    $table->dropIndex(['organisation_id']);
                    $table->dropColumn('organisation_id');
                }
            });
        }

        // Drop foreign keys and columns from events
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                if (Schema::hasColumn('events', 'organisation_id')) {
                    $table->dropForeign(['organisation_id']);
                    $table->dropIndex(['organisation_id']);
                    $table->dropColumn('organisation_id');
                }
            });
        }
    }
};
