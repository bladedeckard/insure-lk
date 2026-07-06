<?php

namespace App\Services\ProductCalculators;

use App\Models\Product;
use Carbon\Carbon;

class PropertyCalculator implements ProductCalculatorInterface
{
    public function __construct(protected Product $product) {}

    public function validate(array $input): array
    {
        $e = [];
        $start = isset($input['start_date']) ? Carbon::parse($input['start_date']) : null;
        if ($start && $start->diffInDays(now(), false) > -7) {
            $e['start_date'] = 'Дата начала не ранее 7 дней от сегодня';
        }
        if (($input['birth_date'] ?? null) && Carbon::parse($input['birth_date'])->age < 18) {
            $e['birth_date'] = 'Возраст не менее 18 лет';
        }
        if (!empty($input['passport_series']) && !preg_match('/^\d{2}\s\d{2}$/', $input['passport_series'])) {
            $e['passport_series'] = 'Формат ХХ ХХ';
        }
        $ps = str_replace(' ', '', $input['passport_series'] ?? '');
        if (strlen($ps)===4 && (int)substr($ps,2) > (int) date('y')) {
            $e['passport_series'] = 'Последние 2 цифры серии не могут быть больше текущего года';
        }
        if (!empty($input['passport_number']) && !preg_match('/^\d{6}$/', $input['passport_number'])) {
            $e['passport_number'] = 'Формат YYYYYY';
        }
        $addr = $input['property_address'] ?? '';
        if ($addr && !str_contains(mb_strtolower($addr), 'кв')) {
            $e['property_address'] = 'Адрес должен содержать номер квартиры';
        }
        return $e;
    }

    public function calculate(array $input): array
    {
        $errors = $this->validate($input);
        if ($errors) return ['premium'=>0,'breakdown'=>[],'errors'=>$errors];

        // Страховые суммы
        $sp1 = (float)($input['sum_construct'] ?? 0);
        $sp2 = (float)($input['sum_finish'] ?? 700000);
        $sp3 = (float)($input['sum_movable'] ?? 700000);
        $sp4 = (float)($input['sum_go'] ?? 200000);
        $sp5 = !empty($input['electricity']) ? max($sp1,$sp2,$sp3) : 0; // в рамках выбранных сумм
        $sp6_1 = (float)($input['exp_keys'] ?? 0);
        $sp6_2 = (float)($input['exp_rent'] ?? 0);
        $sp6_3 = (float)($input['exp_transport'] ?? 0);
        $sp6_4 = (float)($input['exp_return'] ?? 0);

        $tariffs = [
            1 => 0.1504,
            2 => 0.3478,
            3 => 0.752,
            4 => 0.7,
            5 => 0.03,
            '6_1' => 0.42,
            '6_2' => 0.56,
            '6_3' => 0.28,
            '6_4' => 0.2,
        ];
        $k_rent = !empty($input['is_rent']) ? 1.2 : 1.0;

        $base = ($sp1 * $tariffs[1] + $sp2 * $tariffs[2] + $sp3 * $tariffs[3] + $sp5 * $tariffs[5] + $sp4 * $tariffs[4]) / 100 * $k_rent;
        $add = ($sp6_1*$tariffs['6_1'] + $sp6_2*$tariffs['6_2'] + $sp6_3*$tariffs['6_3'] + $sp6_4*$tariffs['6_4']) / 100;

        $premium = round($base + $add, 2);

        return [
            'premium' => $premium,
            'breakdown' => [
                'sum_construct' => $sp1,
                'sum_finish' => $sp2,
                'sum_movable' => $sp3,
                'sum_go' => $sp4,
                'electricity' => $sp5,
                'k_rent' => $k_rent,
                'base_premium' => $base,
                'add_premium' => $add,
            ],
            'errors' => []
        ];
    }
}
