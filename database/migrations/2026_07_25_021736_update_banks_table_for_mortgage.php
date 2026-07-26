<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->dropColumn('load');
            $table->decimal('base_coefficient', 10, 4)->default(0)->after('title_disabled');
            $table->decimal('constructive_coefficient', 10, 6)->default(0)->after('base_coefficient');
            $table->decimal('tariff_bank', 10, 6)->default(0)->after('constructive_coefficient');
            $table->decimal('bank_coefficient_property', 10, 4)->default(0)->after('tariff_bank');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->integer('load')->default(50);
            $table->dropColumn(['base_coefficient', 'constructive_coefficient', 'tariff_bank', 'bank_coefficient_property']);
        });
    }
};
