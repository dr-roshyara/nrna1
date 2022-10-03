<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutcomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outcomes', function (Blueprint $table) {
            $table->id();
            $table->string('country');
            $table->string('committee_name');
            $table->string('period_from');
            $table->string('period_to');
            $table->float('deligate_fee')->nullable();
            $table->float('membership_fee')->nullable();
            $table->float('sponser_fee')->nullable();
            $table->float('donation')->nullable();
           $table->float('salary')->nullable();
            $table->float('rent')->nullable();
            $table->float('software')->nullable();
            $table->float('communication')->nullable();
            $table->float('office_cost')->nullable();
            $table->float('postage')->nullable();
            $table->float('bank_charge')->nullable();
            $table->float('election_cost')->nullable();
            $table->float('equipment')->nullable();
            $table->float('vechicle')->nullable();
            $table->float('website')->nullable();
            $table->float('consulting_charge')->nullable();
            $table->float('training_charge')->nullable();
            $table->float('insurance_charge')->nullable();
            $table->float('guest_invitation')->nullable();
            $table->float('tax_charge')->nullable();
            $table->float('drink')->nullable();
            $table->float('food')->nullable();
            $table->float('event_cost')->nullable();
            $table->float('investment')->nullable();
            $table->float('other_expense')->nullable();
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
        Schema::dropIfExists('outcomes');
    }
}
