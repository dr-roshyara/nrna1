<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleSystemTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Create organisations table
        // Note: id=0 is reserved for platform/system organisation
        Schema::create('organisations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['diaspora', 'ngo', 'professional', 'other'])->default('other');
            $table->json('settings')->nullable();
            $table->json('languages')->nullable();
            $table->timestamps();
        });

        // 2. Create user_organisation_roles pivot table (ADDITIVE - no user_type column)
        Schema::create('user_organisation_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('organisation_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['admin', 'commission', 'voter']);
            $table->json('permissions')->nullable();
            $table->timestamps();

            // Allow multiple roles per user per organisation
            $table->unique(['user_id', 'organisation_id', 'role']);
        });

        // 3. Add organisation_id to elections table (if exists)
        if (Schema::hasTable('elections')) {
            Schema::table('elections', function (Blueprint $table) {
                if (!Schema::hasColumn('elections', 'organisation_id')) {
                    $table->foreignId('organisation_id')
                          ->nullable()
                          ->constrained()
                          ->onDelete('cascade')
                          ->after('id');
                }
            });
        }

        // 4. Create election_commission_members table for election-specific commission roles
        if (Schema::hasTable('elections')) {
            Schema::create('election_commission_members', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('election_id')->constrained()->onDelete('cascade');
                $table->json('permissions')->nullable();
                $table->timestamps();

                // One commission role per user per election
                $table->unique(['user_id', 'election_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop in reverse order
        if (Schema::hasTable('elections')) {
            Schema::dropIfExists('election_commission_members');
        }

        Schema::dropIfExists('user_organisation_roles');

        // Remove from elections table
        if (Schema::hasTable('elections')) {
            Schema::table('elections', function (Blueprint $table) {
                if (Schema::hasColumn('elections', 'organisation_id')) {
                    $table->dropForeign(['organisation_id']);
                    $table->dropColumn('organisation_id');
                }
            });
        }

        Schema::dropIfExists('organisations');
    }
}
