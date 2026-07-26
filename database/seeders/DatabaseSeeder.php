<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{User, Intermediary, Numerator, Dictionary, DictionaryItem};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolesSeeder::class);

        // ─── Посредник ────────────────────────────────────────────────────
        $inter = Intermediary::firstOrCreate(
            ['inn' => '7707083893'],
            [
                'name' => 'ООО Агент Брокер',
                'contract_number' => 'АГ-001',
                'type' => 'legal',
                'is_active' => true,
                'comment' => 'Тестовый посредник'
            ]
        );

        // ─── Пользователи ─────────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@thuricum.ru'],
            ['name' => 'Администратор', 'password' => Hash::make('password'), 'is_active' => true]
        );
        if (!$admin->hasRole('admin')) $admin->assignRole('admin');

        $chief = User::firstOrCreate(
            ['email' => 'chief@thuricum.ru'],
            ['name' => 'Главный менеджер', 'password' => Hash::make('password'), 'is_active' => true]
        );
        if (!$chief->hasRole('chief_manager')) $chief->assignRole('chief_manager');

        $manager = User::firstOrCreate(
            ['email' => 'manager@thuricum.ru'],
            ['name' => 'Менеджер', 'password' => Hash::make('password'), 'is_active' => true]
        );
        if (!$manager->hasRole('manager')) $manager->assignRole('manager');

        $agent = User::firstOrCreate(
            ['email' => 'agent@thuricum.ru'],
            ['name' => 'Агент Петров', 'password' => Hash::make('password'), 'intermediary_id' => $inter->id, 'is_active' => true]
        );
        if (!$agent->hasRole('agent')) $agent->assignRole('agent');

        // ─── Нумераторы ───────────────────────────────────────────────────
        Numerator::firstOrCreate(
            ['name' => 'Полисы Имущество'],
            ['prefix' => 'S380Z', 'include_year' => true, 'year_digits' => 2, 'counter_length' => 6, 'start_value' => 1, 'reset_period' => 'yearly']
        );

        Numerator::firstOrCreate(
            ['name' => 'Ипотека Новосел'],
            ['prefix' => 'NOV', 'include_year' => true, 'year_digits' => 2, 'counter_length' => 7, 'start_value' => 1, 'reset_period' => 'yearly']
        );

        // ─── Словари (банки — legacy) ─────────────────────────────────────
        $banks = Dictionary::firstOrCreate(['code' => 'banks'], ['name' => 'Банки']);

        $bankList = [
            ['key' => 'absolute', 'label' => 'АбсолютБанк', 'data' => ['commission' => 0, 'constructive' => true, 'bank_coeff' => 50, 'osg_coeff' => 1]],
            ['key' => 'akbars', 'label' => 'Акбарс', 'data' => ['commission' => 0, 'constructive' => true, 'bank_coeff' => 50, 'osg_coeff' => 1]],
            ['key' => 'alfa', 'label' => 'Альфабанк', 'data' => ['commission' => 0, 'constructive' => false, 'bank_coeff' => 70, 'osg_coeff' => 1.1]],
            ['key' => 'vtb', 'label' => 'ВТБ', 'data' => ['commission' => 0, 'constructive' => true, 'bank_coeff' => 50, 'osg_coeff' => 1.1]],
            ['key' => 'gpb', 'label' => 'ГПБ', 'data' => ['commission' => 0, 'constructive' => true, 'bank_coeff' => 50, 'osg_coeff' => 1.1]],
            ['key' => 'dom_rf', 'label' => 'Дом РФ', 'data' => ['commission' => 0, 'constructive' => true, 'bank_coeff' => 70, 'osg_coeff' => 1.1]],
            ['key' => 'mkb', 'label' => 'МКБ', 'data' => ['commission' => 0, 'constructive' => true, 'bank_coeff' => 70, 'osg_coeff' => 1]],
            ['key' => 'psb', 'label' => 'Промсвязьбанк', 'data' => ['commission' => 0, 'constructive' => false, 'bank_coeff' => 70, 'osg_coeff' => 1]],
            ['key' => 'raiffeisen', 'label' => 'Райффайзен Банк', 'data' => ['commission' => 0, 'constructive' => false, 'bank_coeff' => 50, 'osg_coeff' => 1.1]],
            ['key' => 'rshb', 'label' => 'РСХБ', 'data' => ['commission' => 0, 'constructive' => true, 'bank_coeff' => 50, 'osg_coeff' => 1]],
            ['key' => 'sber', 'label' => 'Сбербанк', 'data' => ['commission' => 0, 'constructive' => true, 'bank_coeff' => 40, 'osg_coeff' => 1]],
            ['key' => 'tbank', 'label' => 'Т-банк', 'data' => ['commission' => 0, 'constructive' => false, 'bank_coeff' => 50, 'osg_coeff' => 1]],
            ['key' => 'uralsib', 'label' => 'Уралсиб', 'data' => ['commission' => 0, 'constructive' => true, 'bank_coeff' => 50, 'osg_coeff' => 1]],
            ['key' => 'unicredit', 'label' => 'Юникредит', 'data' => ['commission' => 0, 'constructive' => false, 'bank_coeff' => 50, 'osg_coeff' => 1.1]],
            ['key' => 'rshb2', 'label' => 'Россельхозбанк', 'data' => ['commission' => 0, 'constructive' => false, 'bank_coeff' => 50, 'osg_coeff' => 1.1]],
            ['key' => 'spb', 'label' => 'Банк СПБ', 'data' => ['commission' => 0, 'constructive' => false, 'bank_coeff' => 50, 'osg_coeff' => 1]],
        ];

        foreach ($bankList as $b) {
            DictionaryItem::firstOrCreate(
                ['dictionary_id' => $banks->id, 'key' => $b['key']],
                ['label' => $b['label'], 'data' => $b['data']]
            );
        }

        // ─── Новые сущности ───────────────────────────────────────────────
        $this->call(ProductTypeSeeder::class);
        $this->call(BankSeeder::class);

        // ─── Продукты (новый конструктор) ─────────────────────────────────
        $this->call(ProductsConstructorSeeder::class);
    }
}
