<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemoPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demo_posts', function (Blueprint $table) {
            $table->id();
            $table->string('post_id')->unique(); // e.g., "president-1", "vice-president-1"
            $table->string('name'); // e.g., "President", "Vice President"
            $table->string('nepali_name')->nullable(); // e.g., "राष्ट्रपति"
            $table->unsignedBigInteger('election_id'); // Reference to election
            $table->unsignedBigInteger('organisation_id')->nullable(); // NULL=MODE 1, non-NULL=MODE 2
            $table->string('state_name')->nullable(); // e.g., "National", "Province 1"
            $table->integer('position_order')->default(0); // Display order
            $table->integer('required_number')->default(1); // How many can be elected
            $table->boolean('is_national_wide')->default(true); // National vs regional post
            $table->timestamps();

            // Foreign keys
            $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demo_posts');
    }
}
