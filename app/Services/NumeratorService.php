<?php

namespace App\Services;

use App\Models\Numerator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NumeratorService
{
    public function generate(Numerator $numerator, ?Carbon $date = null): string
    {
        $date = $date ?? now();
        $period = $numerator->reset_period === 'yearly' ? $date->format('Y') : 'global';

        return DB::transaction(function () use ($numerator, $date, $period) {
            $counter = $numerator->counters()->lockForUpdate()->firstOrCreate(
                ['period_key' => $period],
                ['last_value' => $numerator->start_value - 1]
            );
            $counter->last_value++;
            $counter->save();

            $yearPart = $numerator->include_year 
                ? ($numerator->year_digits === 2 ? $date->format('y') : $date->format('Y'))
                : '';
            $numPart = str_pad((string)$counter->last_value, $numerator->counter_length, '0', STR_PAD_LEFT);
            return ($numerator->prefix ?? '') . $yearPart . $numPart;
        });
    }
}
