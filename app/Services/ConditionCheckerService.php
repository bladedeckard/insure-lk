<?php

namespace App\Services;

use App\Models\ProductRestriction;

/**
 * Проверка условий ограничений (заказ + андеррайтинг).
 * Поддерживает AND/OR группы, все операторы.
 */
class ConditionCheckerService
{
    /**
     * Проверить ограничение против набора значений.
     * Возвращает true если ограничение СРАБОТАЛО (т.е. нужно блокировать/отправить на согласование).
     */
    public function checkRestriction(ProductRestriction $restriction, array $values): bool
    {
        $conditions = $restriction->conditions;

        if ($conditions->isEmpty()) {
            return false; // нет условий = не срабатывает
        }

        if ($restriction->logic === 'and') {
            foreach ($conditions as $condition) {
                if (!$this->evaluateCondition($condition->field_code, $condition->operator, $condition->value, $values)) {
                    return false;
                }
            }
            return true;
        } else { // OR
            foreach ($conditions as $condition) {
                if ($this->evaluateCondition($condition->field_code, $condition->operator, $condition->value, $values)) {
                    return true;
                }
            }
            return false;
        }
    }

    /**
     * Проверить все ограничения продукта.
     * Возвращает массив сработавших ограничений.
     */
    public function checkAllRestrictions($product, array $values, string $category = 'order'): array
    {
        $triggered = [];
        $restrictions = $product->restrictions()
            ->where('category', $category)
            ->with('conditions')
            ->orderBy('sort_order')
            ->get();

        foreach ($restrictions as $restriction) {
            if ($this->checkRestriction($restriction, $values)) {
                $triggered[] = [
                    'restriction' => $restriction,
                    'action' => $restriction->action,
                    'message' => $restriction->error_message,
                ];
            }
        }

        return $triggered;
    }

    /**
     * Оценить одно условие.
     */
    private function evaluateCondition(string $fieldCode, string $operator, $expectedValue, array $values): bool
    {
        $actualValue = $this->resolveValue($fieldCode, $values);

        return match($operator) {
            '=' => $actualValue == $expectedValue,
            '!=' => $actualValue != $expectedValue,
            '>' => (float)$actualValue > (float)$expectedValue,
            '>=' => (float)$actualValue >= (float)$expectedValue,
            '<' => (float)$actualValue < (float)$expectedValue,
            '<=' => (float)$actualValue <= (float)$expectedValue,
            'in' => is_array($expectedValue) ? in_array($actualValue, $expectedValue) : false,
            'not_in' => is_array($expectedValue) ? !in_array($actualValue, $expectedValue) : true,
            'contains' => str_contains((string)$actualValue, (string)$expectedValue),
            'regex' => (bool)preg_match((string)$expectedValue, (string)$actualValue),
            default => false,
        };
    }

    /**
     * Разрешить значение поля.
     * Поддерживает вычисляемые поля: age, region и т.д.
     */
    private function resolveValue(string $fieldCode, array $values): mixed
    {
        // Вычисляемые поля
        if ($fieldCode === 'age' && isset($values['birthdate'])) {
            try {
                $birth = new \DateTime($values['birthdate']);
                $now = new \DateTime();
                return $now->diff($birth)->y;
            } catch (\Throwable) {
                return null;
            }
        }

        if ($fieldCode === 'sum_insured') {
            // Сумма всех покрытий
            $sum = 0;
            foreach ($values as $key => $val) {
                if (str_starts_with($key, 'sum_') && is_numeric($val)) {
                    $sum += (float)$val;
                }
            }
            return $sum;
        }

        return $values[$fieldCode] ?? null;
    }
}
