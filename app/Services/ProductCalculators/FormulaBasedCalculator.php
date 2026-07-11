<?php

namespace App\Services\ProductCalculators;

use App\Models\Product;
use App\Services\FormulaCalculator;

/**
 * Универсальный калькулятор на основе формулы из config_json.
 * Заменяет PropertyCalculator и MortgageCalculator для новых продуктов.
 */
class FormulaBasedCalculator implements ProductCalculatorInterface
{
    private Product $product;
    private FormulaCalculator $formulaCalc;

    public function __construct(Product $product, FormulaCalculator $formulaCalc)
    {
        $this->product = $product;
        $this->formulaCalc = $formulaCalc;
    }

    public function calculate(array $data): array
    {
        // Собираем значения из data (поля + покрытия)
        $values = [];

        // Добавляем покрытия
        foreach ($this->product->coverages as $coverage) {
            if ($coverage->code) {
                $values[$coverage->code] = $data[$coverage->code] ?? $coverage->default_value ?? 0;
            }
        }

        // Добавляем поля
        foreach ($this->product->fields as $field) {
            if (isset($data[$field->code])) {
                $values[$field->code] = $data[$field->code];
            }
        }

        // Вычисляемые переменные
        if (isset($data['birthdate'])) {
            try {
                $birth = new \DateTime($data['birthdate']);
                $now = new \DateTime();
                $values['age'] = $now->diff($birth)->y;
            } catch (\Throwable) {
                $values['age'] = 0;
            }
        }

        // Рассчитываем премию
        $premium = $this->formulaCalc->calculate($this->product, $values);

        return [
            'premium' => $premium,
            'values' => $values,
            'formula' => $this->product->formula_expression,
        ];
    }

    public function validate(array $data): array
    {
        $errors = [];

        // Проверяем обязательные покрытия
        foreach ($this->product->coverages()->where('required_for_calc', true)->get() as $coverage) {
            if (!isset($data[$coverage->code]) || $data[$coverage->code] === null) {
                $errors[] = "Покрытие '{$coverage->name}' обязательно для расчёта";
            }

            // Проверяем диапазон
            if ($coverage->type === 'range' && isset($data[$coverage->code])) {
                $val = (float)$data[$coverage->code];
                if ($coverage->min_value !== null && $val < $coverage->min_value) {
                    $errors[] = "{$coverage->name}: минимум " . number_format($coverage->min_value);
                }
                if ($coverage->max_value !== null && $val > $coverage->max_value) {
                    $errors[] = "{$coverage->name}: максимум " . number_format($coverage->max_value);
                }
            }
        }

        return $errors;
    }
}
