<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCode1SentAtToTableCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::table('codes', function (Blueprint $table) {
            if (!Schema::hasColumn('codes', 'code1_sent_at')) {
                $table->timestamp('code1_sent_at')->nullable()->after('is_code1_usable');
            }
        });
    }

    public function down()
    {
        Schema::table('codes', function (Blueprint $table) {
            if (Schema::hasColumn('codes', 'code1_sent_at')) {
                $table->dropColumn('code1_sent_at');
            }
        });
    }
}
