<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublishersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publishers', function (Blueprint $table) {
            $table->id();
            $table->string('publisher_id')->unique();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('title');
            $table->boolean('should_agree')->default(true);
            $table->string('authorization_password');
            $table->boolean('is_active')->default(true);
            $table->integer('priority_order')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Foreign key (add only if users table exists)
            if (Schema::hasTable('users')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
            
            // ✅ FIXED: Short index names
            $table->index(['should_agree', 'is_active'], 'publishers_agreement_status_idx');
            $table->index('priority_order', 'publishers_priority_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publishers');
    }
}