<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductField extends Model
{
    protected $fillable = [
        'product_id', 'group_id', 'name', 'code', 'type',
        'required', 'description', 'hint', 'mask', 'regex', 'error_message',
        'options', 'validation_rules', 'visibility_condition',
        'linked_to', 'sort_order', 'row_id',
    ];

    protected $casts = [
        'options' => 'array',
        'validation_rules' => 'array',
        'visibility_condition' => 'array',
        'required' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(ProductFieldGroup::class, 'group_id');
    }

    public function getTypeLabel(): string
    {
        return match($this->type) {
            'text' => 'Текст',
            'number' => 'Число',
            'date' => 'Дата',
            'select' => 'Выпадающий список',
            'checkbox' => 'Чекбокс',
            'phone' => 'Телефон',
            'email' => 'Email',
            'passport_series' => 'Серия паспорта',
            'passport_number' => 'Номер паспорта',
            'birthdate' => 'Дата рождения',
            'address' => 'Адрес (DaData)',
            'file' => 'Файл',
            'textarea' => 'Многострочный текст',
            'group' => 'Группа полей',
            'linked_field' => 'Связанное поле',
            default => $this->type,
        };
    }

    public static function typeOptions(): array
    {
        return [
            'text' => 'Текст',
            'number' => 'Число',
            'date' => 'Дата',
            'select' => 'Выпадающий список',
            'checkbox' => 'Чекбокс (Да/Нет)',
            'phone' => 'Телефон',
            'email' => 'Email',
            'passport_series' => 'Серия паспорта РФ',
            'passport_number' => 'Номер паспорта РФ',
            'birthdate' => 'Дата рождения',
            'address' => 'Адрес (DaData)',
            'file' => 'Загрузка файла',
            'textarea' => 'Многострочный текст',
            'group' => 'Группа полей',
            'linked_field' => 'Связанное поле (совпадает)',
        ];
    }
}
