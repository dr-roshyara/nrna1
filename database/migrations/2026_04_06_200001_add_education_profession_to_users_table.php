<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'middle_name')) {
                $table->string('middle_name')->nullable();
            }
            if (! Schema::hasColumn('users', 'gender')) {
                $table->string('gender')->nullable();
            }
            if (! Schema::hasColumn('users', 'region')) {
                $table->string('region')->nullable();
            } else {
                $table->string('region')->nullable()->change();
            }
            if (! Schema::hasColumn('users', 'country')) {
                $table->string('country')->nullable();
            }
            if (! Schema::hasColumn('users', 'state')) {
                $table->string('state')->nullable();
            }
            if (! Schema::hasColumn('users', 'street')) {
                $table->string('street')->nullable();
            }
            if (! Schema::hasColumn('users', 'housenumber')) {
                $table->string('housenumber')->nullable();
            }
            if (! Schema::hasColumn('users', 'postalcode')) {
                $table->string('postalcode')->nullable();
            }
            if (! Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable();
            }
            if (! Schema::hasColumn('users', 'additional_address')) {
                $table->string('additional_address')->nullable();
            }
            if (! Schema::hasColumn('users', 'telephone')) {
                $table->string('telephone')->nullable();
            }
            if (! Schema::hasColumn('users', 'education_level')) {
                $table->string('education_level')->nullable();
            }
            if (! Schema::hasColumn('users', 'profession')) {
                $table->string('profession')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'middle_name', 'gender', 'region', 'country', 'state',
                'street', 'housenumber', 'postalcode', 'city', 'additional_address',
                'telephone', 'education_level', 'profession',
            ];
            $existing = array_filter($columns, fn ($c) => Schema::hasColumn('users', $c));
            if ($existing) {
                $table->dropColumn(array_values($existing));
            }
        });
    }
};
