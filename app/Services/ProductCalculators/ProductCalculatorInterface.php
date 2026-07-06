<?php

namespace App\Services\ProductCalculators;

use App\Models\Product;

interface ProductCalculatorInterface
{
    public function __construct(Product $product);
    public function calculate(array $input): array; // returns ['premium'=>float, 'breakdown'=>[], 'errors'=>[]]
    public function validate(array $input): array; // field => message
}
