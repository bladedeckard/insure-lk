<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            ['name' => 'АбсолютБанк', 'code' => 'absolut', 'commission' => 0, 'osg_coeff' => 1, 'constructive' => true, 'title_disabled' => false, 'base_coefficient' => 0.4, 'constructive_coefficient' => 0.0017],
            ['name' => 'Акбарс', 'code' => 'akbars', 'commission' => 0, 'osg_coeff' => 1, 'constructive' => true, 'title_disabled' => false, 'base_coefficient' => 0.4, 'constructive_coefficient' => 0.0017],
            ['name' => 'Альфабанк', 'code' => 'alfa', 'commission' => 0, 'osg_coeff' => 1.1, 'constructive' => false, 'title_disabled' => false, 'base_coefficient' => 0.286, 'constructive_coefficient' => 0.0015],
            ['name' => 'ВТБ', 'code' => 'vtb', 'commission' => 0, 'osg_coeff' => 1.1, 'constructive' => true, 'title_disabled' => false, 'base_coefficient' => 0.286, 'constructive_coefficient' => 0.0017],
            ['name' => 'ГПБ', 'code' => 'gpb', 'commission' => 0, 'osg_coeff' => 1.1, 'constructive' => true, 'title_disabled' => false, 'base_coefficient' => 0.286, 'constructive_coefficient' => 0.0017],
            ['name' => 'Дом РФ', 'code' => 'domrf', 'commission' => 0, 'osg_coeff' => 1.1, 'constructive' => true, 'title_disabled' => false, 'base_coefficient' => 0.667, 'constructive_coefficient' => 0.0017],
            ['name' => 'МКБ', 'code' => 'mkb', 'commission' => 0, 'osg_coeff' => 1, 'constructive' => true, 'title_disabled' => false, 'base_coefficient' => 0.286, 'constructive_coefficient' => 0.0017],
            ['name' => 'Промсвязьбанк', 'code' => 'psb', 'commission' => 0, 'osg_coeff' => 1, 'constructive' => false, 'title_disabled' => false, 'base_coefficient' => 0.667, 'constructive_coefficient' => 0.0015],
            ['name' => 'Райффайзен Банк', 'code' => 'raiffeisen', 'commission' => 0, 'osg_coeff' => 1.1, 'constructive' => false, 'title_disabled' => false, 'base_coefficient' => 0.286, 'constructive_coefficient' => 0.0015],
            ['name' => 'РСХБ', 'code' => 'rshb', 'commission' => 0, 'osg_coeff' => 1, 'constructive' => true, 'title_disabled' => false, 'base_coefficient' => 0.286, 'constructive_coefficient' => 0.0017],
            ['name' => 'Сбербанк', 'code' => 'sber', 'commission' => 0, 'osg_coeff' => 1, 'constructive' => true, 'title_disabled' => true, 'base_coefficient' => 0.235, 'constructive_coefficient' => 0.0017],
            ['name' => 'Т-банк', 'code' => 'tbank', 'commission' => 0, 'osg_coeff' => 1, 'constructive' => false, 'title_disabled' => false, 'base_coefficient' => 0.286, 'constructive_coefficient' => 0.0015],
            ['name' => 'Уралсиб', 'code' => 'uralsib', 'commission' => 0, 'osg_coeff' => 1, 'constructive' => true, 'title_disabled' => false, 'base_coefficient' => 0.4, 'constructive_coefficient' => 0.0017],
            ['name' => 'Юникредит', 'code' => 'unicredit', 'commission' => 0, 'osg_coeff' => 1.1, 'constructive' => false, 'title_disabled' => false, 'base_coefficient' => 0.286, 'constructive_coefficient' => 0.0015],
            ['name' => 'Россельхозбанк', 'code' => 'rshb2', 'commission' => 0, 'osg_coeff' => 1.1, 'constructive' => false, 'title_disabled' => false, 'base_coefficient' => 0.4, 'constructive_coefficient' => 0.0015],
            ['name' => 'Банк СПБ', 'code' => 'spb', 'commission' => 0, 'osg_coeff' => 1, 'constructive' => false, 'title_disabled' => false, 'base_coefficient' => 0.4, 'constructive_coefficient' => 0.0015],
        ];

        foreach ($banks as $bank) {
            // Calculate tariff_bank and bank_coefficient_property
            $bank['tariff_bank'] = $bank['base_coefficient'] * $bank['constructive_coefficient'];
            if ($bank['tariff_bank'] > 0) {
                $bank['bank_coefficient_property'] = 1 / (0.0017 / $bank['tariff_bank']);
            } else {
                $bank['bank_coefficient_property'] = 0;
            }

            Bank::updateOrCreate(
                ['code' => $bank['code']],
                $bank
            );
        }

        $this->command->info('Bank seeder completed - ' . count($banks) . ' banks');
    }
}
