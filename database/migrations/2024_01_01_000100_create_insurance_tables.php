<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Dictionaries
        Schema::create('dictionaries', function (Blueprint $t) {
            $t->id();
            $t->string('code')->unique(); // e.g. banks, regions
            $t->string('name');
            $t->json('meta')->nullable();
            $t->timestamps();
        });
        Schema::create('dictionary_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('dictionary_id')->constrained()->cascadeOnDelete();
            $t->string('key');
            $t->string('label');
            $t->json('data')->nullable(); // extra coefficients etc
            $t->integer('sort')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->unique(['dictionary_id','key']);
        });

        // Numerators
        Schema::create('numerators', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('prefix')->nullable(); // e.g. S380Z
            $t->boolean('include_year')->default(true);
            $t->unsignedTinyInteger('year_digits')->default(2);
            $t->unsignedInteger('counter_length')->default(6);
            $t->unsignedBigInteger('start_value')->default(1);
            $t->enum('reset_period', ['never','yearly'])->default('never');
            $t->timestamps();
        });
        Schema::create('numerator_counters', function (Blueprint $t) {
            $t->id();
            $t->foreignId('numerator_id')->constrained()->cascadeOnDelete();
            $t->string('period_key'); // e.g. 2026 or 'global'
            $t->unsignedBigInteger('last_value');
            $t->timestamps();
            $t->unique(['numerator_id','period_key']);
        });

        // Products
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->string('code')->unique();
            $t->string('name');
            $t->text('description')->nullable();
            $t->foreignId('numerator_id')->nullable()->constrained()->nullOnDelete();
            $t->string('calculator_class'); // App\Services\ProductCalculators\PropertyCalculator
            $t->json('config_json'); // fields, risks, validation, templates
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        // Policies
        Schema::create('policies', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained();
            $t->string('number')->unique()->nullable();
            $t->foreignId('intermediary_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('created_by')->constrained('users');
            $t->enum('status', ['draft','pending_approval','issued','cancelled'])->default('draft');
            $t->date('start_date')->nullable();
            $t->date('end_date')->nullable();
            $t->json('data_json'); // all form data
            $t->json('calculation_json')->nullable();
            $t->decimal('premium', 12, 2)->default(0);
            $t->string('policyholder_email')->nullable();
            $t->string('policyholder_phone')->nullable();
            $t->text('comment')->nullable();
            $t->timestamp('issued_at')->nullable();
            $t->timestamps();
            $t->index(['product_id','status']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('policies');
        Schema::dropIfExists('products');
        Schema::dropIfExists('numerator_counters');
        Schema::dropIfExists('numerators');
        Schema::dropIfExists('dictionary_items');
        Schema::dropIfExists('dictionaries');
    }
};
