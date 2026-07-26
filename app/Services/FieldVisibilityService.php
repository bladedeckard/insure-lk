<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductField;

/**
 * Сервис проверки видимости полей в форме полиса.
 * 
 * Два уровня проверки (Гибрид A + B):
 * A) Привязка к покрытиям через pivot (product_field_coverages)
 * B) JSON-условия через visibility_condition
 * 
 * Логика:
 * - Если у поля НЕТ привязок И НЕТ условий → показывается ВСЕГДА
 * - Если есть привязки к покрытиям → показывается когда хотя бы одно покрытие активно
 * - Если есть visibility_condition → показывается когда условие истинно
 * - Если есть ОБА → показывается когда оба уровня выполнены (AND)
 */
class FieldVisibilityService
{
    private ConditionCheckerService $conditionChecker;

    public function __construct(ConditionCheckerService $conditionChecker)
    {
        $this->conditionChecker = $conditionChecker;
    }

    /**
     * Проверить, должно ли поле быть видимым при текущих данных.
     */
    public function isVisible(ProductField $field, array $formData, Product $product): bool
    {
        // Уровень A: привязка к покрытиям
        $coverageIds = $field->coverages->pluck('id')->toArray();
        
        if (!empty($coverageIds)) {
            $anyCoverageActive = false;
            
            foreach ($field->coverages as $coverage) {
                $code = $coverage->code;
                if (!$code) continue;

                $value = $formData[$code] ?? null;

                // Для flag: активно если true/1
                if ($coverage->type === 'flag') {
                    if ($value === true || $value === 1 || $value === '1') {
                        $anyCoverageActive = true;
                        break;
                    }
                }
                // Для range: активно если > 0
                elseif ($coverage->type === 'range' || $coverage->type === 'constant') {
                    if (is_numeric($value) && (float)$value > 0) {
                        $anyCoverageActive = true;
                        break;
                    }
                }
                // Для set: активно если выбрано ненулевое значение
                elseif ($coverage->type === 'set') {
                    if (is_numeric($value) && (float)$value > 0) {
                        $anyCoverageActive = true;
                        break;
                    }
                }
            }

            if (!$anyCoverageActive) {
                return false;
            }
        }

        // Уровень B: JSON-условия видимости
        $condition = $field->visibility_condition;
        if (!empty($condition) && is_array($condition)) {
            return $this->evaluateCondition($condition, $formData);
        }

        return true;
    }

    /**
     * Оценить JSON-условие видимости.
     */
    private function evaluateCondition(array $condition, array $formData): bool
    {
        $logic = $condition['logic'] ?? 'and';
        $conditions = $condition['conditions'] ?? [];

        if (empty($conditions)) {
            return true;
        }

        if ($logic === 'and') {
            foreach ($conditions as $cond) {
                if (!$this->evaluateSingleCondition($cond, $formData)) {
                    return false;
                }
            }
            return true;
        } else { // or
            foreach ($conditions as $cond) {
                if ($this->evaluateSingleCondition($cond, $formData)) {
                    return true;
                }
            }
            return false;
        }
    }

    /**
     * Оценить одно условие.
     */
    private function evaluateSingleCondition(array $cond, array $formData): bool
    {
        $fieldCode = $cond['field_code'] ?? '';
        $operator = $cond['operator'] ?? '=';
        $expectedValue = $cond['value'] ?? '';
        $actualValue = $formData[$fieldCode] ?? null;

        return match($operator) {
            '=' => $actualValue == $expectedValue,
            '!=' => $actualValue != $expectedValue,
            '>' => is_numeric($actualValue) && (float)$actualValue > (float)$expectedValue,
            '>=' => is_numeric($actualValue) && (float)$actualValue >= (float)$expectedValue,
            '<' => is_numeric($actualValue) && (float)$actualValue < (float)$expectedValue,
            '<=' => is_numeric($actualValue) && (float)$actualValue <= (float)$expectedValue,
            'in' => is_array($expectedValue) ? in_array($actualValue, $expectedValue) : $actualValue == $expectedValue,
            'not_in' => is_array($expectedValue) ? !in_array($actualValue, $expectedValue) : $actualValue != $expectedValue,
            'contains' => str_contains((string)$actualValue, (string)$expectedValue),
            'regex' => (bool)@preg_match((string)$expectedValue, (string)$actualValue),
            'empty' => empty($actualValue),
            'not_empty' => !empty($actualValue),
            default => true,
        };
    }

    /**
     * Построить карту видимости для всех полей продукта (для Alpine.js).
     * Возвращает массив: [field_code => {coverage_codes: [...], conditions: [...]}]
     */
    public function buildVisibilityMap(Product $product): array
    {
        $map = [];

        foreach ($product->fields as $field) {
            $entry = [];

            // Привязки к покрытиям
            $coverageCodes = $field->coverages->pluck('code')->filter()->values()->toArray();
            if (!empty($coverageCodes)) {
                $entry['coverage_codes'] = $coverageCodes;
                // Типы покрытий для правильной проверки
                $entry['coverage_types'] = $field->coverages
                    ->mapWithKeys(fn($c) => [$c->code => $c->type])
                    ->toArray();
            }

            // JSON-условия
            if (!empty($field->visibility_condition)) {
                $entry['condition'] = $field->visibility_condition;
            }

            if (!empty($entry)) {
                $map[$field->code] = $entry;
            }
        }

        return $map;
    }
}
