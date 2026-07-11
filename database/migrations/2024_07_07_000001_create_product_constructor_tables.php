<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── Расширяем таблицу products ───────────────────────────────────
        Schema::table('products', function (Blueprint $t) {
            // Новая основная информация
            $t->string('marketing_name')->nullable()->after('name');
            $t->string('currency', 10)->default('RUB')->after('description');
            
            // Версионирование
            $t->enum('status', ['draft', 'published', 'archived'])->default('draft')->after('is_active');
            $t->unsignedInteger('current_version')->default(0)->after('status');
            
            // Настройки заказа
            $t->unsignedInteger('period_start_days')->default(7)->after('config_json'); // через N дней от сегодня
            $t->unsignedInteger('period_end_value')->default(1)->after('period_start_days');
            $t->enum('period_end_unit', ['days', 'years'])->default('years')->after('period_end_value');
            
            // Формула (Expression Language)
            $t->text('formula_expression')->nullable()->after('calculator_class');
            $t->json('formula_variables')->nullable()->after('formula_expression'); // справочник переменных
            
            // Дополнительные настройки
            $t->boolean('send_email')->default(true)->after('period_end_unit');
            $t->string('email_field')->nullable()->after('send_email'); // из какого поля брать email
            $t->boolean('allow_edit_start_date')->default(true)->after('email_field');
            
            // Андеррайтинг
            $t->text('approval_emails')->nullable()->after('allow_edit_start_date'); // через запятую
        });

        // ─── Посредники продукта (pivot) ──────────────────────────────────
        Schema::create('product_intermediaries', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->foreignId('intermediary_id')->constrained()->cascadeOnDelete();
            $t->unique(['product_id', 'intermediary_id']);
        });

        // ─── Покрытия / Риски ─────────────────────────────────────────────
        Schema::create('product_coverages', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->string('name');                     // Конструктивные элементы
            $t->string('code')->nullable();          // sum_construct
            $t->enum('type', ['range', 'constant', 'set', 'flag'])->default('range');
            $t->decimal('min_value', 15, 2)->nullable();
            $t->decimal('max_value', 15, 2)->nullable();
            $t->decimal('default_value', 15, 2)->nullable();
            $t->json('set_values')->nullable();      // для type=set: [0, 5000, 10000]
            $t->boolean('required_for_calc')->default(true); // обязательно для расчёта
            $t->integer('sort_order')->default(0);
            $t->json('risks')->nullable();           // массив рисков ["Пожар","Залив",...]
            $t->timestamps();
        });

        // ─── Группы полей ─────────────────────────────────────────────────
        Schema::create('product_field_groups', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->string('name');                      // "Об объекте страхования"
            $t->string('code')->nullable();           // object_info
            $t->text('description')->nullable();
            $t->integer('sort_order')->default(0);
            $t->timestamps();
        });

        // ─── Поля формы полиса ────────────────────────────────────────────
        Schema::create('product_fields', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->foreignId('group_id')->nullable()->constrained('product_field_groups')->nullOnDelete();
            $t->string('name');                      // "ФИО Страхователя"
            $t->string('code');                      // policyholder_last_name
            $t->enum('type', [
                'text', 'number', 'date', 'select', 'checkbox', 'phone', 'email',
                'passport_series', 'passport_number', 'birthdate', 'address',
                'file', 'textarea', 'group', 'linked_field'
            ])->default('text');
            $t->boolean('required')->default(true);
            $t->text('description')->nullable();     // описание поля
            $t->text('hint')->nullable();             // подсказка
            $t->string('mask')->nullable();           // маска ввода
            $t->string('regex')->nullable();          // regex-валидация
            $t->string('error_message')->nullable();  // сообщение при ошибке валидации
            $t->json('options')->nullable();          // для select: [{"value":"kv","label":"Квартира"}]
            $t->json('validation_rules')->nullable(); // доп. правила
            $t->json('visibility_condition')->nullable(); // условие видимости
            $t->string('linked_to')->nullable();      // связь с другим полем (совпадает)
            $t->integer('sort_order')->default(0);
            $t->timestamps();
            
            $t->unique(['product_id', 'code']);
        });

        // ─── Ограничения (заказ + андеррайтинг) ───────────────────────────
        Schema::create('product_restrictions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->enum('category', ['order', 'underwriting'])->default('order');
            $t->enum('action', ['block', 'approval'])->default('block');
            $t->string('error_message')->nullable();  // сообщение при срабатывании
            $t->enum('logic', ['and', 'or'])->default('and'); // логика между условиями
            $t->integer('sort_order')->default(0);
            $t->timestamps();
        });

        // ─── Условия ограничений ──────────────────────────────────────────
        Schema::create('product_restriction_conditions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('restriction_id')->constrained('product_restrictions')->cascadeOnDelete();
            $t->string('field_code');    // левый операнд (код поля или покрытие)
            $t->string('operator');      // =, !=, >, >=, <, <=, in, not_in, contains, regex
            $t->json('value');           // правый операнд
            $t->string('group_id')->nullable(); // для группировки AND/OR
            $t->integer('sort_order')->default(0);
            $t->timestamps();
        });

        // ─── Шаблоны документов ───────────────────────────────────────────
        Schema::create('product_documents', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->enum('type', ['policy', 'kid', 'application'])->default('policy');
            $t->string('name');
            $t->string('file_path');                  // storage/app/templates/...
            $t->boolean('is_enabled')->default(true);
            $t->json('apply_conditions')->nullable(); // условия применения шаблона
            $t->integer('sort_order')->default(0);
            $t->timestamps();
        });

        // ─── Пользовательские соглашения ──────────────────────────────────
        Schema::create('product_agreements', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->text('text');                          // текст соглашения
            $t->boolean('required')->default(true);
            $t->integer('sort_order')->default(0);
            $t->timestamps();
        });

        // ─── Декларации ───────────────────────────────────────────────────
        Schema::create('product_declarations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->string('name');                        // "Декларация по НС"
            $t->text('text');                           // текст декларации
            $t->boolean('is_active')->default(true);
            $t->boolean('required')->default(true);
            $t->json('show_conditions')->nullable();   // условия показа
            $t->integer('sort_order')->default(0);
            $t->timestamps();
        });

        // ─── Версии продукта ──────────────────────────────────────────────
        Schema::create('product_versions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->unsignedInteger('version');
            $t->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $t->json('snapshot');                      // полная копия продукта в JSON
            $t->foreignId('created_by')->constrained('users');
            $t->text('change_note')->nullable();
            $t->timestamps();
            
            $t->unique(['product_id', 'version']);
        });

        // ─── Лог изменений продукта ───────────────────────────────────────
        Schema::create('product_version_logs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained();
            $t->string('action');                      // created, updated, published, archived, rollback
            $t->json('diff')->nullable();               // что изменилось
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_version_logs');
        Schema::dropIfExists('product_versions');
        Schema::dropIfExists('product_declarations');
        Schema::dropIfExists('product_agreements');
        Schema::dropIfExists('product_documents');
        Schema::dropIfExists('product_restriction_conditions');
        Schema::dropIfExists('product_restrictions');
        Schema::dropIfExists('product_fields');
        Schema::dropIfExists('product_field_groups');
        Schema::dropIfExists('product_coverages');
        Schema::dropIfExists('product_intermediaries');

        Schema::table('products', function (Blueprint $t) {
            $t->dropColumn([
                'marketing_name', 'currency', 'status', 'current_version',
                'period_start_days', 'period_end_value', 'period_end_unit',
                'formula_expression', 'formula_variables',
                'send_email', 'email_field', 'allow_edit_start_date', 'approval_emails'
            ]);
        });
    }
};
