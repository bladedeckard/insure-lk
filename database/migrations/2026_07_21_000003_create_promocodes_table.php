<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promocodes', function (Blueprint $t) {
            $t->id();
            $t->string('code');
            $t->decimal('discount_percent', 5, 2);
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->date('valid_from')->nullable();
            $t->date('valid_to')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();

            $t->unique(['code', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promocodes');
    }
};
