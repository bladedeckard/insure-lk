<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banks', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('code')->unique();
            $t->decimal('commission', 5, 2)->default(0);
            $t->decimal('osg_coeff', 5, 4)->default(1.0);
            $t->boolean('constructive')->default(false);
            $t->boolean('title_disabled')->default(false);
            $t->integer('load')->default(50);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
