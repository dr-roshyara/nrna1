<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCode2SentAtToCodesTable extends Migration
{
    public function up()
    {
        Schema::table('codes', function (Blueprint $table) {
            if (!Schema::hasColumn('codes', 'code2_sent_at')) {
                $table->timestamp('code2_sent_at')->nullable()->after('is_code2_usable');
            }
        });
    }

    public function down()
    {
        Schema::table('codes', function (Blueprint $table) {
            if (Schema::hasColumn('codes', 'code2_sent_at')) {
                $table->dropColumn('code2_sent_at');
            }
        });
    }
}