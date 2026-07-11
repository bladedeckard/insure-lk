<?php

namespace App\Services;

use App\Models\Product;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\SyntaxError;

/**
 * Калькулятор страховой премии на основе Symfony Expression Language.
 * Менеджер пишет формулу текстом, переменные подставляются из Покрытий и Полей.
 */
class FormulaCalculator
{
    private ExpressionLanguage $el;

    public function __construct()
    {
        $this->el = new ExpressionLanguage();

        // Регистрируем безопасные функции
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
        $this->el->register(
            'if',
            fn($cond, $then, $else) => sprintf('(%s) ? (%s) : (%s)', $cond, $then, $else),
            fn($cond, $then, $else) => $cond ? $then : $else
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

        // Добавляем значения покрытий
        foreach ($product->coverages as $coverage) {
            if ($coverage->code && !isset($values[$coverage->code])) {
                $values[$coverage->code] = $coverage->default_value ?? 0;
            }
        }

        // Убеждаемся что все числовые значения — числа
        foreach ($values as $key => $val) {
            if (is_numeric($val)) {
                $values[$key] = (float)$val;
            }
            // Boolean (флаги) конвертируем в 0/1
            if (is_bool($val)) {
                $values[$key] = $val ? 1 : 0;
            }
        }

        try {
            $result = $this->el->evaluate($expression, $values);
            return round((float)$result, 2);
        } catch (\Throwable $e) {
            throw new \RuntimeException("Ошибка расчёта формулы: {$e->getMessage()}");
        }
    }

    /**
     * Валидация формулы (синтаксис).
     */
    public function validate(string $expression, array $variableNames = []): array
    {
        $errors = [];

        if (empty(trim($expression))) {
            $errors[] = 'Формула не может быть пустой';
            return $errors;
        }

        // Создаём тестовые значения для всех переменных
        $testValues = [];
        foreach ($variableNames as $name) {
            $testValues[$name] = 1;
        }

        try {
            $this->el->evaluate($expression, $testValues);
        } catch (SyntaxError $e) {
            $errors[] = 'Синтаксическая ошибка: ' . $e->getMessage();
        } catch (\Throwable $e) {
            $errors[] = 'Ошибка вычисления: ' . $e->getMessage();
        }

        return $errors;
    }

    /**
     * Тестовый расчёт с подстановкой (для предпросмотра в UI).
     */
    public function testCalculate(string $expression, array $testValues): array
    {
        try {
            $result = $this->el->evaluate($expression, $testValues);
            return ['success' => true, 'result' => round((float)$result, 2)];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Получить все переменные из формулы (для автодополнения).
     */
    public function extractVariables(string $expression): array
    {
        // Извлекаем все идентификаторы из формулы
        preg_match_all('/\b([a-zA-Z_][a-zA-Z0-9_]*)\b/', $expression, $matches);

        // Убираем зарезервированные слова и функции
        $reserved = ['true', 'false', 'null', 'and', 'or', 'not', 'in', 'matches',
                     'max', 'min', 'round', 'abs', 'if'];
        
        $variables = array_diff($matches[1], $reserved);
        return array_unique($variables);
    }
}
