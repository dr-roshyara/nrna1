<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('country');
            $table->string('committee_name');
            $table->string('period_from');
            $table->string('period_to');
            $table->float('membership_fee')->nullable();
            $table->float('nomination_fee')->nullable();
            $table->float('sponser_fee')->nullable();
            $table->float('donation')->nullable();
            $table->float('levy')->nullable();
            $table->float('event_fee')->nullable();
            $table->float('event_income')->nullable();
            $table->float('event_contribution')->nullable();
            $table->float('deligate_fee')->nullable();
            $table->float('deligate_contribution')->nullable();
            $table->float('interest_income')->nullable();
            $table->float('business_income')->nullable();
            $table->float('other_incomes')->nullable();


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
        Schema::dropIfExists('incomes');
    }
}
