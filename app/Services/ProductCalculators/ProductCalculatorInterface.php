<?php

namespace App\Services\ProductCalculators;

interface ProductCalculatorInterface
{
    /**
     * Рассчитать страховую премию и дополнительные параметры.
     *
     * @param array $data Данные из формы полиса
     * @return array ['premium' => float, ...]
     */
    public function calculate(array $data): array;

    /**
     * Валидация данных перед расчётом.
     *
     * @param array $data
     * @return array Массив ошибок (пустой если всё ОК)
     */
    public function validate(array $data): array;
}
