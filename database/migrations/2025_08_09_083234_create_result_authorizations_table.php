<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultAuthorizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_authorizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('publisher_id');
            $table->string('authorization_session_id');
            $table->boolean('agreed')->default(false);
            $table->timestamp('agreed_at')->nullable();
            $table->boolean('password_verified')->default(false);
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->text('concerns')->nullable();
            $table->boolean('is_valid')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->json('verification_data')->nullable();
            $table->timestamps();
            
            // ⚠️ Foreign keys commented out for now - add them later
            // $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
            // $table->foreign('publisher_id')->references('id')->on('publishers')->onDelete('cascade');
            
            // ✅ FIXED: Shorter constraint and index names
            $table->unique(['election_id', 'publisher_id', 'authorization_session_id'], 'auth_unique_constraint');
            
            // ✅ FIXED: Short index names
            $table->index(['election_id', 'agreed'], 'auth_election_agreed_idx');
            $table->index(['authorization_session_id', 'agreed'], 'auth_session_agreed_idx');
            $table->index('agreed_at', 'auth_agreed_at_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('result_authorizations');
    }
}