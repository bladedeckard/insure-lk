<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    public function run(): void
    {
        ProductType::updateOrCreate(
            ['code' => 'mortgage'],
            [
                'name' => 'Ипотечное страхование',
                'description' => 'Комплексное ипотечное страхование: жизнь, имущество, титул',
                'calculator_class' => 'App\Services\ProductCalculators\MortgageCalculator',
                'config_json' => [
                    'max_load_percent' => 60,
                    'requires_bank' => true,
                    'title_requires_property' => true,
                    'title_disabled_banks' => ['sber'],
                    'approval_thresholds' => [
                        'life' => 10000000,
                        'property' => 10000000,
                    ],
                    'base_tariffs' => [
                        'life' => 0.70,
                        'property_constructive' => 0.27,
                        'property_no_constructive' => 0.25,
                        'title' => 0.43,
                    ],
                    'reinsurance' => [
                        'property_apartment' => 0.0355,
                        'property_wood' => 0.068,
                        'property_stone' => 0.0645,
                        'property_mixed' => 0.0785,
                        'title' => 0.08,
                    ],
                    'room_type_coefficients' => [
                        'house' => 2.2,
                        'apartment' => 1.0,
                        'non_residential' => 1.4,
                        'land' => 0.8,
                    ],
                    'cover_type_coefficients' => [
                        'stone' => 0.8,
                        'mixed' => 1.0,
                        'wood' => 1.2,
                    ],
                    'house_age_coefficients' => [
                        ['min' => 0, 'max' => 20, 'coeff' => 0.7],
                        ['min' => 20, 'max' => 29, 'coeff' => 0.8],
                        ['min' => 30, 'max' => 60, 'coeff' => 0.9],
                    ],
                    'age_sex_coefficients' => [
                        [18, 20, 0.25, 0.15],
                        [21, 25, 0.28, 0.165],
                        [26, 27, 0.31, 0.18],
                        [28, 29, 0.32, 0.19],
                        [30, 31, 0.345, 0.2],
                        [32, 33, 0.355, 0.215],
                        [34, 35, 0.37, 0.23],
                        [36, 37, 0.39, 0.25],
                        [38, 39, 0.425, 0.28],
                        [40, 41, 0.47, 0.33],
                        [42, 43, 0.55, 0.39],
                        [44, 46, 0.63, 0.505],
                        [47, 49, 0.72, 0.605],
                        [50, 52, 0.86, 0.745],
                        [53, 55, 0.98, 0.88],
                        [56, 58, 1.15, 1.05],
                        [59, 63, 1.25, 1.15],
                        [64, 65, 1.35, 1.25],
                    ],
                    'rnpk_life_rates' => [
                        18 => [0.041996, 0.021648], 19 => [0.048576, 0.022992],
                        20 => [0.056472, 0.024336], 21 => [0.063052, 0.02568],
                        22 => [0.067, 0.02568], 23 => [0.069632, 0.027024],
                        24 => [0.070948, 0.027024], 25 => [0.072264, 0.028368],
                        26 => [0.07358, 0.028368], 27 => [0.074896, 0.031056],
                        28 => [0.078844, 0.0324], 29 => [0.084108, 0.035088],
                        30 => [0.090688, 0.036432], 31 => [0.095952, 0.037776],
                        32 => [0.098584, 0.037776], 33 => [0.103848, 0.040464],
                        34 => [0.111744, 0.044496], 35 => [0.118324, 0.047184],
                        36 => [0.124904, 0.049872], 37 => [0.1328, 0.053904],
                        38 => [0.140696, 0.057936], 39 => [0.15254, 0.061968],
                        40 => [0.163068, 0.066], 41 => [0.173596, 0.071376],
                        42 => [0.184124, 0.076752], 43 => [0.195968, 0.084816],
                        44 => [0.210444, 0.09288], 45 => [0.22492, 0.102288],
                        46 => [0.240712, 0.11304], 47 => [0.260452, 0.125136],
                        48 => [0.281508, 0.137232], 49 => [0.2973, 0.14664],
                        50 => [0.315724, 0.15336], 51 => [0.339412, 0.161424],
                        52 => [0.367048, 0.17352], 53 => [0.397316, 0.188304],
                        54 => [0.427584, 0.201744], 55 => [0.456536, 0.217872],
                        56 => [0.486804, 0.239376], 57 => [0.517072, 0.263568],
                        58 => [0.549972, 0.291792], 59 => [0.585504, 0.322704],
                        60 => [0.622352, 0.357648], 61 => [0.660516, 0.39528],
                        62 => [0.701312, 0.438288], 63 => [0.74474, 0.483984],
                        64 => [0.7908, 0.5364], 65 => [0.840808, 0.592848],
                    ],
                ],
                'is_active' => true,
            ]
        );

        ProductType::updateOrCreate(
            ['code' => 'property'],
            [
                'name' => 'Страхование имущества',
                'description' => 'Страхование квартиры, дома, имущества',
                'calculator_class' => 'App\Services\ProductCalculators\FormulaBasedCalculator',
                'config_json' => [
                    'max_load_percent' => 60,
                    'requires_bank' => false,
                    'title_requires_property' => false,
                    'title_disabled_banks' => [],
                    'approval_thresholds' => [],
                ],
                'is_active' => true,
            ]
        );

        $this->command->info('ProductType seeder completed');
    }
}
