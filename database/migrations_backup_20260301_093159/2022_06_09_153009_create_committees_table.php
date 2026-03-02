<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommitteesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('committees', function (Blueprint $table) {
            $table->id();
            $table->string('hierarchal_label');
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->dateTime('period_from')->nullable();
            $table->dateTime('period_to')->nullable();
            $table->unsignedInteger('period_number')->nullable();
            $table->dateTime('election_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('committees');
    }
}
