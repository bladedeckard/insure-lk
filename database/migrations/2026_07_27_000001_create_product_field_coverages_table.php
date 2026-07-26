<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_field_coverages', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_field_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_coverage_id')->constrained()->cascadeOnDelete();
            $t->unique(['product_field_id', 'product_coverage_id'], 'pfc_field_coverage_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_field_coverages');
    }
};
