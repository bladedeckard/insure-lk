<?php

namespace App\Services\ProductCalculators;

use App\Models\Product;
use Carbon\Carbon;

class MortgageCalculator implements ProductCalculatorInterface
{
    // Упрощенная реализация по ТЗ
    public function __construct(protected Product $product) {}

    public function validate(array $input): array
    {
        $e = [];
        if (($input['birth_date'] ?? null)) {
            $age = Carbon::parse($input['birth_date'])->age;
            if ($age < 18) $e['birth_date'] = 'Возраст не менее 18';
            if ($age > 60) $e['birth_date'] = 'Возраст не более 60';
        }
        if (!empty($input['passport_series']) && !preg_match('/^\d{2}\s\d{2}$/', $input['passport_series'])) {
            $e['passport_series'] = 'Формат ХХ ХХ';
        }
        return $e;
    }

    private function ageSexCoeff(int $age, string $sex): float
    {
        $map = [
            [18,20,0.25,0.15],[21,25,0.28,0.165],[26,27,0.31,0.18],[28,29,0.32,0.19],
            [30,31,0.345,0.2],[32,33,0.355,0.215],[34,35,0.37,0.23],[36,37,0.39,0.25],
            [38,39,0.425,0.28],[40,41,0.47,0.33],[42,43,0.55,0.39],[44,46,0.63,0.505],
            [47,49,0.72,0.605],[50,52,0.86,0.745],[53,55,0.98,0.88],[56,58,1.15,1.05],
            [59,63,1.25,1.15],[64,65,1.35,1.25],
        ];
        foreach($map as [$a,$b,$m,$f]) if($age>=$a && $age<=$b) return $sex==='m' ? $m : $f;
        return 1.0;
    }

    private function reLifeRate(int $age, string $sex): float
    {
        $table = [
18=>[0.041996,0.021648],19=>[0.048576,0.022992],20=>[0.056472,0.024336],
21=>[0.063052,0.02568],22=>[0.067,0.02568],23=>[0.069632,0.027024],24=>[0.070948,0.027024],
25=>[0.072264,0.028368],26=>[0.07358,0.028368],27=>[0.074896,0.031056],28=>[0.078844,0.0324],
29=>[0.084108,0.035088],30=>[0.090688,0.036432],31=>[0.095952,0.037776],32=>[0.098584,0.037776],
33=>[0.103848,0.040464],34=>[0.111744,0.044496],35=>[0.118324,0.047184],36=>[0.124904,0.049872],
37=>[0.1328,0.053904],38=>[0.140696,0.057936],39=>[0.15254,0.061968],40=>[0.163068,0.066],
41=>[0.173596,0.071376],42=>[0.184124,0.076752],43=>[0.195968,0.084816],44=>[0.210444,0.09288],
45=>[0.22492,0.102288],46=>[0.240712,0.11304],47=>[0.260452,0.125136],48=>[0.281508,0.137232],
49=>[0.2973,0.14664],50=>[0.315724,0.15336],51=>[0.339412,0.161424],52=>[0.367048,0.17352],
53=>[0.397316,0.188304],54=>[0.427584,0.201744],55=>[0.456536,0.217872],56=>[0.486804,0.239376],
57=>[0.517072,0.263568],58=>[0.549972,0.291792],59=>[0.585504,0.322704],60=>[0.622352,0.357648],
61=>[0.660516,0.39528],62=>[0.701312,0.438288],63=>[0.74474,0.483984],64=>[0.7908,0.5364],
65=>[0.840808,0.592848],
        ];
        return $table[$age][$sex==='m'?0:1] ?? 0.1;
    }

    public function calculate(array $input): array
    {
        $errors = $this->validate($input);
        $osg = (float)($input['osg'] ?? 0);
        $bank_coeff_osg = (float)($input['bank_osg_coeff'] ?? 1.0);
        $sum_insured = $osg * $bank_coeff_osg;

        $needs_approval = false;
        $risks = $input['risks'] ?? [];
        if (in_array('life', $risks) && $sum_insured > 10_000_000) $needs_approval = true;
        if (in_array('property', $risks) && $sum_insured > 10_000_000) $needs_approval = true;
        if (in_array('title', $risks)) $needs_approval = true;

        $bank = $input['bank'] ?? 'sber';
        // Упрощенные коэффициенты банков
        $bank_load = ['sber'=>40,'vtb'=>50,'alfa'=>70][$bank] ?? 50;
        // таблица нагрузка->поправочный
        $bank_table = [40=>0.333,50=>0.4,70=>0.667];
        $k_bank = $bank_table[$bank_load] ?? 0.4;

        $promo_k = 1.0;
        if (!empty($input['promocode'])) {
            $promo_map = ['NEW10'=>0.9,'SPRING'=>0.95];
            $promo_k = $promo_map[strtoupper($input['promocode'])] ?? 1.0;
        }
        $intermediary_k = 0.9; // если есть посредник
        if (empty($input['intermediary_id'])) $intermediary_k = 1.0;

        $premium_total = 0;
        $breakdown = [];

        // Имущество
        if (in_array('property', $risks)) {
            $room_type = $input['room_type'] ?? 'apartment';
            $k_type = ['house'=>2.2,'apartment'=>1,'non_res'=>1.2,'land'=>0.8][$room_type] ?? 1;
            $cover = $input['cover_type'] ?? 'stone';
            $k_cover = ['stone'=>0.8,'mixed'=>1,'wood'=>1.2][$cover] ?? 1;
            $age_house = (int)($input['house_age'] ?? 10);
            $k_year = $age_house < 20 ? 0.7 : ($age_house < 30 ? 0.8 : 0.9);
            $base_tariff_prop = !empty($input['constructive']) ? 0.27 : 0.25;
            $std_prop = $base_tariff_prop * $k_cover * $k_type * $k_year * $k_bank * $intermediary_k * $promo_k / $intermediary_k;
            // перестрахование
            $re_tariff = $room_type==='apartment' ? 0.0355 : ['wood'=>0.068,'stone'=>0.0645,'mixed'=>0.0785][$cover] ?? 0.0645;
            $re_prop = $re_tariff * $promo_k / $intermediary_k;
            $tariff_prop = max($std_prop, $re_prop);
            $premium_prop = $sum_insured * $tariff_prop / 100;
            $premium_total += $premium_prop;
            $breakdown['property'] = round($premium_prop,2);
        }

        // Жизнь
        if (in_array('life', $risks)) {
            $birth = $input['birth_date'] ?? null;
            $age = $birth ? Carbon::parse($birth)->age : 35;
            $sex = $input['sex'] ?? 'm';
            $k_age_sex = $this->ageSexCoeff($age, $sex);
            $k_sport = !empty($input['extreme_sport']) ? 1.5 : 1;
            $k_job = !empty($input['danger_job']) ? 1.5 : 1;
            $base_tariff_life = 0.70;
            $std_life = $base_tariff_life * $k_age_sex * $k_sport * $k_job * $k_bank * $intermediary_k * $promo_k / $intermediary_k;
            $re_life = $this->reLifeRate(min(max($age,18),65), $sex) * $promo_k / $intermediary_k;
            $tariff_life = max($std_life, $re_life);
            $premium_life = $sum_insured * $tariff_life / 100;
            $premium_total += $premium_life;
            $breakdown['life'] = round($premium_life,2);
        }

        // Титул
        if (in_array('title', $risks)) {
            $base_tariff_title = 0.43;
            $std_title = $base_tariff_title * $intermediary_k * $promo_k / $intermediary_k;
            $re_title = 0.08 * $promo_k / $intermediary_k;
            $tariff_title = max($std_title, $re_title);
            $premium_title = $sum_insured * $tariff_title / 100;
            $premium_total += $premium_title;
            $breakdown['title'] = round($premium_title,2);
        }

        return [
            'premium' => round($premium_total,2),
            'breakdown' => $breakdown + ['sum_insured'=>$sum_insured,'needs_approval'=>$needs_approval],
            'errors' => $errors,
            'needs_approval' => $needs_approval,
        ];
    }
}
