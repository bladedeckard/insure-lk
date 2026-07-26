<?php

namespace App\Services\ProductCalculators;

use App\Models\Bank;
use App\Models\Product;
use App\Models\Promocode;
use App\Models\Intermediary;
use Carbon\Carbon;

class MortgageCalculator implements ProductCalculatorInterface
{
    public function __construct(protected Product $product) {}

    public function validate(array $input): array
    {
        $e = [];
        $birthDate = $input['birthdate'] ?? $input['birth_date'] ?? null;
        if ($birthDate) {
            $age = Carbon::parse($birthDate)->age;
            if ($age < 18) $e['birthdate'] = 'Возраст не менее 18 лет';
            if ($age > 65) $e['birthdate'] = 'Возраст не более 65 лет';
        }
        return $e;
    }

    /**
     * Перевод кодовых значений на русский
     */
    private function translateCode(string $code, string $type = ''): string
    {
        $translations = [
            'apartment' => 'Квартира',
            'house' => 'Дом',
            'non_residential' => 'Нежилое',
            'stone' => 'Каменный',
            'mixed' => 'Смешанный',
            'wood' => 'Деревянный',
            'm' => 'Мужской',
            'f' => 'Женский',
            'male' => 'Мужской',
            'female' => 'Женский',
            'yes' => 'Да',
            'no' => 'Нет',
        ];
        return $translations[$code] ?? $code;
    }

    /**
     * Базовый тариф НС по возрасту и полу (из ТЗ, диапазоны)
     */
    private function baseTariffLife(int $age, string $sex): float
    {
        // [min_age, max_age, мужчина, женщина]
        $ranges = [
            [18, 20, 0.18000, 0.18000],
            [21, 25, 0.20700, 0.19350],
            [26, 27, 0.23400, 0.20700],
            [28, 29, 0.24300, 0.21600],
            [30, 31, 0.26550, 0.22500],
            [32, 33, 0.27450, 0.23850],
            [34, 35, 0.28800, 0.25200],
            [36, 37, 0.30600, 0.27000],
            [38, 39, 0.33750, 0.29700],
            [40, 41, 0.37800, 0.34200],
            [42, 43, 0.45000, 0.39600],
            [44, 46, 0.52200, 0.49950],
            [47, 49, 0.60300, 0.58950],
            [50, 52, 0.72900, 0.71550],
            [53, 55, 0.83700, 0.83700],
            [56, 58, 0.99000, 0.99000],
            [59, 63, 1.08000, 1.08000],
            [64, 65, 1.17000, 1.17000],
        ];

        foreach ($ranges as [$min, $max, $male, $female]) {
            if ($age >= $min && $age <= $max) {
                return $sex === 'm' ? $male : $female;
            }
        }
        return 1.0;
    }

    /**
     * Базовый тариф перестрахования жизнь (пол + возраст) из ТЗ
     */
    private function reinsuranceLifeRate(int $age, string $sex): float
    {
        $table = [
            18 => [0.0434, 0.0238],
            19 => [0.0504, 0.0252],
            20 => [0.0588, 0.0266],
            21 => [0.0658, 0.0280],
            22 => [0.0700, 0.0280],
            23 => [0.0728, 0.0294],
            24 => [0.0742, 0.0294],
            25 => [0.0756, 0.0308],
            26 => [0.0770, 0.0308],
            27 => [0.0784, 0.0336],
            28 => [0.0826, 0.0350],
            29 => [0.0882, 0.0378],
            30 => [0.0952, 0.0392],
            31 => [0.1008, 0.0406],
            32 => [0.1036, 0.0406],
            33 => [0.1092, 0.0434],
            34 => [0.1176, 0.0476],
            35 => [0.1246, 0.0504],
            36 => [0.1316, 0.0532],
            37 => [0.1400, 0.0574],
            38 => [0.1484, 0.0616],
            39 => [0.1610, 0.0658],
            40 => [0.1722, 0.0700],
            41 => [0.1834, 0.0756],
            42 => [0.1946, 0.0812],
            43 => [0.2072, 0.0896],
            44 => [0.2226, 0.0980],
            45 => [0.2380, 0.1078],
            46 => [0.2548, 0.1190],
            47 => [0.2758, 0.1316],
            48 => [0.2982, 0.1442],
            49 => [0.3150, 0.1540],
            50 => [0.3346, 0.1610],
            51 => [0.3598, 0.1694],
            52 => [0.3892, 0.1820],
            53 => [0.4214, 0.1974],
            54 => [0.4536, 0.2114],
            55 => [0.4844, 0.2282],
            56 => [0.5166, 0.2506],
            57 => [0.5488, 0.2758],
            58 => [0.5838, 0.3052],
            59 => [0.6216, 0.3374],
            60 => [0.6608, 0.3738],
            61 => [0.7014, 0.4130],
            62 => [0.7448, 0.4578],
            63 => [0.7910, 0.5054],
            64 => [0.8400, 0.5600],
            65 => [0.8932, 0.6188],
        ];
        $age = max(18, min(65, $age));
        $rates = $table[$age] ?? [0.1, 0.1];
        return $sex === 'm' ? $rates[0] : $rates[1];
    }

    /**
     * Базовый тариф перестрахования имущества из ТЗ
     */
    private function reinsurancePropertyRate(string $roomType, string $coverType): float
    {
        if ($roomType === 'apartment') {
            return 0.00025; // 0.025%
        }
        // Дом — смотрим тип перекрытия
        $rates = [
            'wood' => 0.0007,  // 0.07%
            'stone' => 0.00045, // 0.045%
            'mixed' => 0.0005,  // 0.05%
        ];
        return $rates[$coverType] ?? 0.0005;
    }

    /**
     * Коэффициент типа помещения
     */
    private function roomTypeCoeff(string $roomType): float
    {
        $map = ['house' => 2.2, 'apartment' => 1.0];
        return $map[$roomType] ?? 1.0;
    }

    /**
     * Коэффициент перекрытия
     */
    private function coverTypeCoeff(string $coverType): float
    {
        $map = ['stone' => 0.8, 'mixed' => 1.0, 'wood' => 1.2];
        return $map[$coverType] ?? 1.0;
    }

    /**
     * Коэффициент возраста объекта
     */
    private function houseAgeCoeff(int $age): float|false
    {
        if ($age >= 61) return false; // requires approval
        if ($age <= 20) return 0.7;
        if ($age <= 29) return 0.8;
        if ($age <= 59) return 0.9;
        return 1.0;
    }

    /**
     * Коэффициент промокода (скидка)
     */
    private function getPromoCoeff(array $input): float
    {
        if (empty($input['promocode'])) return 1.0;
        $promo = Promocode::where('code', strtoupper($input['promocode']))
            ->where('product_id', $this->product->id)
            ->active()
            ->validNow()
            ->first();
        return $promo ? $promo->getDiscountCoefficient() : 1.0;
    }

    /**
     * Коэффициент посредника = 1 - (КВ / 100)
     */
    private function getIntermediaryCoeff(array $input): float
    {
        // If KV is provided directly from the form, use it
        if (!empty($input['kv_percent'])) {
            $kv = (float)$input['kv_percent'];
            if ($kv > 0) {
                return 1 - ($kv / 100);
            }
        }

        // Otherwise, try to look up from product_intermediaries
        if (empty($input['intermediary_id'])) return 1.0;
        $intermediary = Intermediary::find($input['intermediary_id']);
        if (!$intermediary) return 1.0;
        $pivot = \DB::table('product_intermediaries')
            ->where('product_id', $this->product->id)
            ->where('intermediary_id', $intermediary->id)
            ->first();
        $maxKv = $pivot->max_commission ?? 0;
        if ($maxKv <= 0) return 1.0;
        return 1 - ($maxKv / 100);
    }

    /**
     * Коэффициент надбавки (0-100% → 1.00-2.00)
     */
    private function getMarkupCoeff(array $input): float
    {
        $markup = (float)($input['markup_percent'] ?? 0);
        if ($markup <= 0) return 1.0;
        return 1 + ($markup / 100);
    }

    public function calculate(array $input): array
    {
        $errors = $this->validate($input);

        $bankCode = $input['bank'] ?? null;
        $bank = $bankCode ? Bank::where('code', $bankCode)->active()->first() : null;

        // Остаток суммы задолженности (ОСЗ) — пользователь вводит в поле "Страховая сумма"
        $osg = (float)($input['insurance_sum'] ?? 0);

        // Коэффициент ОСЗ банка (требование банка к страховой сумме)
        $osgCoeff = $bank ? (float)$bank->osg_coeff : 1.0;

        // Страховая сумма = ОСЗ × Коэфф. ОСЗ
        $insuranceSum = $osg * $osgCoeff;

        // Определяем риски
        $risks = [];
        if (!empty($input['risk_life'])) $risks[] = 'life';
        if (!empty($input['risk_property'])) $risks[] = 'property';
        if (!empty($input['risk_title'])) $risks[] = 'title';

        // Пороги согласования
        $needsApproval = false;
        if (in_array('life', $risks) && $insuranceSum > 10_000_000) $needsApproval = true;
        if (in_array('property', $risks) && $insuranceSum > 10_000_000) $needsApproval = true;
        if (in_array('title', $risks)) $needsApproval = true;

        // Возраст объекта
        $houseAge = (int)($input['house_age'] ?? 0);
        if (in_array('property', $risks) && $houseAge >= 61) $needsApproval = true;

        // Проверка титула
        if (in_array('title', $risks)) {
            if ($bank && $bank->title_disabled) {
                $errors['title'] = 'Титульное страхование недоступно для выбранного банка';
            }
            if (!in_array('property', $risks)) {
                $errors['title'] = 'Титульное страхование доступно только при покрытии "Имущество"';
            }
        }

        // Коэффициенты
        $promoK = $this->getPromoCoeff($input);
        $intermediaryK = $this->getIntermediaryCoeff($input);
        $markupK = $this->getMarkupCoeff($input);

        // Взаимоисключающие промокод и надбавка
        if ($promoK < 1.0 && $markupK > 1.0) {
            $markupK = 1.0; // промокод отменяет надбавку
        }

        // Тарифы из конфига продукта
        $productTariffs = $this->product->config_json['tariffs'] ?? [];
        $baseTariffProperty = 0.0017; // 0.17% из ТЗ
        $baseTariffTitle = 0.0033; // 0.33% из ТЗ
        $reinsuranceTitle = 0.0008; // 0.08% из ТЗ

        $premiumTotal = 0;
        $breakdown = [];

        // === ИМУЩЕСТВО ===
        if (in_array('property', $risks)) {
            $roomType = $input['room_type'] ?? 'apartment';
            $coverType = $input['cover_type'] ?? 'stone';

            $kType = $this->roomTypeCoeff($roomType);
            $kCover = $this->coverTypeCoeff($coverType);
            $kYear = $this->houseAgeCoeff($houseAge);
            if ($kYear === false) $kYear = 0.9;

            // Коэффициент банка (имущество)
            $kBankProperty = $bank ? (float)$bank->bank_coefficient_property : 1.0;

            // Стандартный расчёт: тариф × коэффициенты
            $stdProp = $baseTariffProperty * $kCover * $kType * $kYear * $kBankProperty;

            // Перестрахование
            $reProp = $this->reinsurancePropertyRate($roomType, $coverType);

            // Максимум из стандартного и перестрахования
            $tariffProp = max($stdProp, $reProp);

            // Премия = сумма × тариф × промокод/надбавка / коэфф.посредника
            $premiumProp = $insuranceSum * $tariffProp * $promoK * $markupK / $intermediaryK;

            $premiumTotal += $premiumProp;
            $breakdown['property'] = round($premiumProp, 2);
            $breakdown['property_tariff'] = round($tariffProp * 100, 4);
            $breakdown['property_eff_tariff'] = round($tariffProp * $promoK * $markupK / $intermediaryK * 100, 4);
            $breakdown['property_std'] = round($stdProp * 100, 4);
            $breakdown['property_re'] = round($reProp * 100, 4);
            $breakdown['property_room'] = $this->translateCode($roomType);
            $breakdown['property_cover'] = $this->translateCode($coverType);
            $breakdown['property_house_age'] = $houseAge;
        }

        // === ЖИЗНЬ (НС) ===
        if (in_array('life', $risks)) {
            $birth = $input['birthdate'] ?? $input['birth_date'] ?? null;
            $age = $birth ? Carbon::parse($birth)->age : 35;
            $sexRaw = $input['sex'] ?? 'm';
            $sex = in_array($sexRaw, ['m', 'male', 'Мужской']) ? 'm' : 'f';
            $kSport = !empty($input['extreme_sport']) ? 1.5 : 1.0;
            $kJob = ($input['dangerous_activity'] ?? 'no') === 'yes' ? 1.5 : 1.0;

            // Базовый тариф НС (зависит от возраста и пола)
            $baseLife = $this->baseTariffLife($age, $sex) / 100; // в процентах

            // Базовый коэффициент банка
            $kBankBase = $bank ? (float)$bank->base_coefficient : 1.0;

            // Стандартный расчёт: тариф × спорт × деятельность × базовый коэф банка
            $stdLife = $baseLife * $kSport * $kJob * $kBankBase;

            // Перестрахование
            $reLife = $this->reinsuranceLifeRate($age, $sex) / 100;

            // Максимум из стандартного и перестрахования
            $tariffLife = max($stdLife, $reLife);

            // Премия = сумма × тариф × промокод/надбавка / коэфф.посредника
            $premiumLife = $insuranceSum * $tariffLife * $promoK * $markupK / $intermediaryK;

            $premiumTotal += $premiumLife;
            $breakdown['life'] = round($premiumLife, 2);
            $breakdown['life_tariff'] = round($tariffLife * 100, 4);
            $breakdown['life_eff_tariff'] = round($tariffLife * $promoK * $markupK / $intermediaryK * 100, 4);
            $breakdown['life_std'] = round($stdLife * 100, 4);
            $breakdown['life_re'] = round($reLife * 100, 4);
            $breakdown['life_bank_coeff'] = $kBankBase;
            $breakdown['life_age'] = $age;
            $breakdown['life_sex'] = $this->translateCode($sex);
            $breakdown['life_base_tariff'] = round($baseLife * 100, 4);
            $breakdown['life_sport'] = $kSport;
            $breakdown['life_job'] = $kJob;
        }

        // === ТИТУЛ ===
        if (in_array('title', $risks)) {
            // Базовый коэффициент банка
            $kBankBase = $bank ? (float)$bank->base_coefficient : 1.0;

            // Стандартный расчёт: тариф × базовый коэф банка
            $stdTitle = $baseTariffTitle * $kBankBase;

            // Перестрахование
            $reTitle = $reinsuranceTitle;

            // Максимум
            $tariffTitle = max($stdTitle, $reTitle);

            // Премия = сумма × тариф × промокод/надбавка / коэфф.посредника
            $premiumTitle = $insuranceSum * $tariffTitle * $promoK * $markupK / $intermediaryK;

            $premiumTotal += $premiumTitle;
            $breakdown['title'] = round($premiumTitle, 2);
            $breakdown['title_tariff'] = round($tariffTitle * 100, 4);
            $breakdown['title_eff_tariff'] = round($tariffTitle * $promoK * $markupK / $intermediaryK * 100, 4);
            $breakdown['title_std'] = round($stdTitle * 100, 4);
            $breakdown['title_re'] = round($reTitle * 100, 4);
        }

        return [
            'premium' => round($premiumTotal, 2),
            'breakdown' => $breakdown + [
                'osg' => $osg,
                'osg_coeff' => $osgCoeff,
                'insurance_sum' => $insuranceSum,
                'risks' => $risks,
                'bank_code' => $bankCode,
                'bank_coefficient_property' => $bank ? (float)$bank->bank_coefficient_property : null,
                'promo_coeff' => $promoK,
                'markup_coeff' => $markupK,
                'intermediary_coeff' => $intermediaryK,
            ],
            'errors' => $errors,
            'needs_approval' => $needsApproval,
        ];
    }
}
