<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommitteeTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('committee_types', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('hierarchical_number')->unsigned()->nullable(false);
            $table->string('short_name')->nullable();
            $table->string('full_name');
            $table->string('scope'); 
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
        Schema::dropIfExists('committee_types');
    }
}
