<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_intermediaries', function (Blueprint $t) {
            $t->decimal('max_commission', 5, 2)->nullable()->after('intermediary_id');
        });
    }

    public function down(): void
    {
        Schema::table('product_intermediaries', function (Blueprint $t) {
            $t->dropColumn('max_commission');
        });
    }
};
