<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Intermediary;
use App\Models\Numerator;
use App\Models\Product;
use App\Models\Dictionary;
use App\Models\DictionaryItem;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolesSeeder::class);

        $inter = Intermediary::create([
            'name'=>'ООО Агент Брокер',
            'inn'=>'7707083893',
            'contract_number'=>'АГ-001',
            'type'=>'legal',
            'is_active'=>true,
            'comment'=>'Тестовый посредник'
        ]);

        $admin = User::create([
            'name'=>'Администратор',
            'email'=>'admin@thuricum.ru',
            'password'=>Hash::make('password'),
            'is_active'=>true
        ]);
        $admin->assignRole('admin');

        $chief = User::create([
            'name'=>'Главный менеджер',
            'email'=>'chief@thuricum.ru',
            'password'=>Hash::make('password'),
            'is_active'=>true
        ]);
        $chief->assignRole('chief_manager');

        $manager = User::create([
            'name'=>'Менеджер',
            'email'=>'manager@thuricum.ru',
            'password'=>Hash::make('password'),
            'is_active'=>true
        ]);
        $manager->assignRole('manager');

        $agent = User::create([
            'name'=>'Агент Петров',
            'email'=>'agent@thuricum.ru',
            'password'=>Hash::make('password'),
            'intermediary_id'=>$inter->id,
            'is_active'=>true
        ]);
        $agent->assignRole('agent');

        // Нумераторы
        $num1 = Numerator::create([
            'name'=>'Полисы Имущество',
            'prefix'=>'S380Z',
            'include_year'=>true,
            'year_digits'=>2,
            'counter_length'=>6,
            'start_value'=>1,
            'reset_period'=>'yearly'
        ]);
        $num2 = Numerator::create([
            'name'=>'Ипотека Новосел',
            'prefix'=>'NOV',
            'include_year'=>true,
            'year_digits'=>2,
            'counter_length'=>7,
            'start_value'=>1,
            'reset_period'=>'yearly'
        ]);

        // Словари - банки
        $banks = Dictionary::create(['code'=>'banks','name'=>'Банки']);
        $bankList = [
            ['key'=>'sber','label'=>'Сбербанк','data'=>['commission'=>0,'constructive'=>true,'bank_coeff'=>40,'osg_coeff'=>1]],
            ['key'=>'vtb','label'=>'ВТБ','data'=>['commission'=>0,'constructive'=>true,'bank_coeff'=>50,'osg_coeff'=>1.1]],
            ['key'=>'alfa','label'=>'Альфабанк','data'=>['commission'=>0,'constructive'=>false,'bank_coeff'=>70,'osg_coeff'=>1.1]],
        ];
        foreach($bankList as $b){ DictionaryItem::create(['dictionary_id'=>$banks->id]+$b); }

        // Продукт Имущество Страху.Нет
        Product::create([
            'code'=>'property',
            'name'=>'Страхование квартиры «Страху.Нет»',
            'description'=>'Мы гарантируем надежную защиту вашей квартиры от потенциальных угроз',
            'numerator_id'=>$num1->id,
            'calculator_class'=>'App\Services\ProductCalculators\PropertyCalculator',
            'config_json'=>[
                'fields'=>[
                    'property_address','area','sum_construct','sum_finish','sum_movable','sum_go','electricity','is_rent','start_date',
                    'last_name','first_name','middle_name','birth_date','passport_series','passport_number','phone','email'
                ],
                'template'=>'property_policy.docx'
            ],
            'is_active'=>true
        ]);

        // Продукт Ипотека Новосел
        Product::create([
            'code'=>'mortgage',
            'name'=>'Страхование квартиры «Новосел»',
            'description'=>'Ипотечное страхование',
            'numerator_id'=>$num2->id,
            'calculator_class'=>'App\Services\ProductCalculators\MortgageCalculator',
            'config_json'=>[
                'fields'=>['bank','risks','osg','room_type','cover_type','house_age','birth_date','sex'],
                'template'=>'mortgage_policy.docx'
            ],
            'is_active'=>true
        ]);
    }
}
