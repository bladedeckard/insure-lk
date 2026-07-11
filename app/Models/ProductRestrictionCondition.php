<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductRestrictionCondition extends Model
{
    protected $fillable = [
        'restriction_id', 'field_code', 'operator', 'value', 'group_id', 'sort_order',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    public function restriction(): BelongsTo
    {
        return $this->belongsTo(ProductRestriction::class, 'restriction_id');
    }

    public function getOperatorLabel(): string
    {
        return match($this->operator) {
            '=' => 'Равно',
            '!=' => 'Не равно',
            '>' => 'Больше',
            '>=' => 'Больше или равно',
            '<' => 'Меньше',
            '<=' => 'Меньше или равно',
            'in' => 'В списке',
            'not_in' => 'Не в списке',
            'contains' => 'Содержит',
            'regex' => 'Соответствует regex',
            default => $this->operator,
        };
    }

    public static function operatorOptions(): array
    {
        return [
            '=' => 'Равно',
            '!=' => 'Не равно',
            '>' => 'Больше',
            '>=' => 'Больше или равно',
            '<' => 'Меньше',
            '<=' => 'Меньше или равно',
            'in' => 'В списке',
            'not_in' => 'Не в списке',
            'contains' => 'Содержит',
            'regex' => 'Соответствует regex',
        ];
    }
}
