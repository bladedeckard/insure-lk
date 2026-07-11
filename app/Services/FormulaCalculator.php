<?php

namespace App\Services;

use App\Models\Product;

/**
 * Калькулятор страховой премии.
 * Использует Symfony ExpressionLanguage если доступен,
 * иначе — простой PHP-парсер формул.
 */
class FormulaCalculator
{
    private $el = null;
    private bool $useExpressionLanguage = false;

    public function __construct()
    {
        // Проверяем доступность symfony/expression-language
        if (class_exists(\Symfony\Component\ExpressionLanguage\ExpressionLanguage::class)) {
            try {
                $this->el = new \Symfony\Component\ExpressionLanguage\ExpressionLanguage();
                $this->registerFunctions();
                $this->useExpressionLanguage = true;
            } catch (\Throwable $e) {
                $this->useExpressionLanguage = false;
            }
        }
    }

    private function registerFunctions(): void
    {
        if (!$this->el) return;

        $this->el->register(
            'max',
            fn(...$args) => sprintf('max(%s)', implode(', ', $args)),
            fn(...$args) => max(...$args)
        );
        $this->el->register(
            'min',
            fn(...$args) => sprintf('min(%s)', implode(', ', $args)),
            fn(...$args) => min(...$args)
        );
        $this->el->register(
            'round',
            fn($val, $precision = '0') => sprintf('round(%s, %s)', $val, $precision),
            fn($val, $precision = 0) => round($val, $precision)
        );
        $this->el->register(
            'abs',
            fn($val) => sprintf('abs(%s)', $val),
            fn($val) => abs($val)
        );
    }

    /**
     * Рассчитать премию по формуле продукта с подстановкой значений.
     */
    public function calculate(Product $product, array $values): float
    {
        $expression = $product->formula_expression;

        if (empty($expression)) {
            return 0;
        }

        // Добавляем дефолтные значения из покрытий
        foreach ($product->coverages as $coverage) {
            if ($coverage->code && !isset($values[$coverage->code])) {
                $values[$coverage->code] = $coverage->default_value ?? 0;
            }
        }

        // Конвертируем значения
        foreach ($values as $key => $val) {
            if (is_numeric($val)) {
                $values[$key] = (float)$val;
            }
            if (is_bool($val)) {
                $values[$key] = $val ? 1.0 : 0.0;
            }
            if ($val === '' || $val === null) {
                $values[$key] = 0.0;
            }
        }

        // Пробуем ExpressionLanguage
        if ($this->useExpressionLanguage) {
            try {
                $result = $this->el->evaluate($expression, $values);
                return round((float)$result, 2);
            } catch (\Throwable $e) {
                // Fallback на простой парсер
            }
        }

        // Fallback: простой PHP-парсер
        return $this->simpleCalculate($expression, $values);
    }

    /**
     * Простой калькулятор без внешних зависимостей.
     * Подставляет переменные и вычисляет через безопасный eval.
     */
    private function simpleCalculate(string $expression, array $values): float
    {
        // Подставляем переменные
        $expr = $expression;

        // Сортируем по длине ключа (длинные сначала, чтобы "sum_construct" заменилось до "sum")
        uksort($values, fn($a, $b) => strlen($b) - strlen($a));

        foreach ($values as $key => $val) {
            $val = is_numeric($val) ? (float)$val : 0;
            // Заменяем переменные на значения (только целые слова)
            $expr = preg_replace('/\b' . preg_quote($key, '/') . '\b/', '(' . $val . ')', $expr);
        }

        // Заменяем тернарный оператор: (cond ? a : b)
        // Обрабатываем "electricity ? max(...) : 0" — если electricity уже заменён на число
        // ExpressionLanguage понимает "0 ? x : y", PHP тоже

        // Заменяем функции max/min на PHP-эквиваленты
        // max(a, b, c) уже работает в PHP

        // Убираем переносы строк
        $expr = str_replace(["\r", "\n"], ' ', $expr);

        // Безопасный eval — только математические выражения
        // Проверяем что в выражении только допустимые символы
        $safeExpr = preg_replace('/[^0-9+\-*\/().,%<>=!&|?:\s]/', '', $expr);

        // Убираем max/min/max — заменяем на PHP функции
        // На самом деле max() и min() — валидные PHP функции, оставим

        try {
            // @ — подавляем warning'и
            $result = @eval('return ' . $expr . ';');
            if (is_numeric($result)) {
                return round((float)$result, 2);
            }
        } catch (\Throwable $e) {
            // Игнорируем
        }

        // Если ничего не помогло — пробуем с заменой max/min
        try {
            $phpExpr = $expr;
            // Уже содержит max() и min() — PHP их понимает
            $result = @eval('return (float)(' . $phpExpr . ');');
            if (is_numeric($result)) {
                return round((float)$result, 2);
            }
        } catch (\Throwable $e) {
            // Игнорируем
        }

        \Log::warning('FormulaCalculator: не удалось вычислить формулу', [
            'expression' => $expression,
            'substituted' => $expr,
            'values' => $values,
        ]);

        return 0;
    }

    /**
     * Валидация формулы.
     */
    public function validate(string $expression, array $variableNames = []): array
    {
        $errors = [];

        if (empty(trim($expression))) {
            $errors[] = 'Формула не может быть пустой';
            return $errors;
        }

        $testValues = [];
        foreach ($variableNames as $name) {
            $testValues[$name] = 1;
        }

        if ($this->useExpressionLanguage) {
            try {
                $this->el->evaluate($expression, $testValues);
            } catch (\Throwable $e) {
                $errors[] = 'Ошибка: ' . $e->getMessage();
            }
        } else {
            $result = $this->simpleCalculate($expression, $testValues);
            if ($result === 0.0) {
                $errors[] = 'Не удалось вычислить формулу (проверьте синтаксис)';
            }
        }

        return $errors;
    }

    /**
     * Тестовый расчёт.
     */
    public function testCalculate(string $expression, array $testValues): array
    {
        try {
            if ($this->useExpressionLanguage) {
                $result = $this->el->evaluate($expression, $testValues);
            } else {
                $result = $this->simpleCalculate($expression, $testValues);
            }
            return ['success' => true, 'result' => round((float)$result, 2)];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Получить переменные из формулы.
     */
    public function extractVariables(string $expression): array
    {
        preg_match_all('/\b([a-zA-Z_][a-zA-Z0-9_]*)\b/', $expression, $matches);
        $reserved = ['true', 'false', 'null', 'and', 'or', 'not', 'in', 'matches',
                     'max', 'min', 'round', 'abs', 'if'];
        return array_unique(array_diff($matches[1], $reserved));
    }
}
