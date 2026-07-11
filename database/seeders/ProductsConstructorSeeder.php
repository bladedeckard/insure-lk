<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    Product, ProductCoverage, ProductField, ProductFieldGroup,
    ProductRestriction, ProductRestrictionCondition, ProductDocument,
    ProductAgreement, ProductDeclaration, Numerator, Dictionary
};

class ProductsConstructorSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedStrahuNet();
        $this->seedNovosel();
    }

    /**
     * Продукт 1: Страхование квартиры «Страху.Нет»
     */
    private function seedStrahuNet(): void
    {
        $numerator = Numerator::where('name', 'Полисы Имущество')->first();
        if (!$numerator) {
            $numerator = Numerator::create([
                'name' => 'Полисы Имущество',
                'prefix' => 'S380Z',
                'include_year' => true,
                'year_digits' => 2,
                'counter_length' => 6,
                'start_value' => 1,
                'reset_period' => 'yearly'
            ]);
        }

        // Удаляем старый продукт если есть
        Product::where('code', 'property')->delete();

        $product = Product::create([
            'code' => 'property',
            'name' => 'Страхование квартиры «Страху.Нет»',
            'marketing_name' => 'Страху.Нет',
            'description' => 'Мы гарантируем надежную защиту вашей квартиры от потенциальных угроз, таких как заливы, пожары, кражи и другие неприятности.',
            'numerator_id' => $numerator->id,
            'calculator_class' => 'App\\Services\\ProductCalculators\\FormulaBasedCalculator',
            'config_json' => [],
            'formula_expression' => '(
  sum_construct * 0.1504 +
  sum_finish * 0.3478 +
  sum_movable * 0.752 +
  sum_go * 0.7 +
  (electricity ? max(sum_construct, sum_finish, sum_movable) : 0) * 0.03
) / 100 * k_rent +
  exp_keys * 0.42 / 100 +
  exp_rent * 0.56 / 100 +
  exp_transport * 0.28 / 100 +
  exp_return * 0.2 / 100',
            'formula_variables' => [],
            'currency' => 'RUB',
            'is_active' => true,
            'status' => 'published',
            'current_version' => 1,
            'period_start_days' => 7,
            'period_end_value' => 1,
            'period_end_unit' => 'years',
            'send_email' => true,
            'email_field' => 'policyholder_email',
            'allow_edit_start_date' => true,
            'approval_emails' => null,
        ]);

        // ─── Покрытия ─────────────────────────────────────────────────────
        $coverages = [
            [
                'name' => 'Конструктивные элементы',
                'code' => 'sum_construct',
                'type' => 'range',
                'min_value' => 0,
                'max_value' => 2000000,
                'default_value' => 0,
                'required_for_calc' => true,
                'sort_order' => 1,
                'risks' => ['Пожар', 'Удар молнии', 'Повреждение водой', 'Стихийные бедствия', 'Противоправные действия третьих лиц', 'Постороннее воздействие', 'Уничтожение или повреждение застрахованного имущества по неосторожности', 'Вандализм', 'Хулиганство', 'Взрыв'],
            ],
            [
                'name' => 'Внутренняя отделка и инженерное оборудование',
                'code' => 'sum_finish',
                'type' => 'range',
                'min_value' => 1,
                'max_value' => 2000000,
                'default_value' => 700000,
                'required_for_calc' => true,
                'sort_order' => 2,
                'risks' => ['Пожар', 'Удар молнии', 'Повреждение водой', 'Стихийные бедствия', 'Противоправные действия третьих лиц', 'Постороннее воздействие', 'Уничтожение или повреждение застрахованного имущества по неосторожности', 'Вандализм', 'Хулиганство', 'Взрыв'],
            ],
            [
                'name' => 'Движимое имущество',
                'code' => 'sum_movable',
                'type' => 'range',
                'min_value' => 1,
                'max_value' => 1000000,
                'default_value' => 700000,
                'required_for_calc' => true,
                'sort_order' => 3,
                'risks' => ['Пожар', 'Удар молнии', 'Повреждение водой', 'Стихийные бедствия', 'Противоправные действия третьих лиц', 'Постороннее воздействие', 'Уничтожение или повреждение застрахованного имущества по неосторожности', 'Вандализм', 'Хулиганство', 'Взрыв'],
            ],
            [
                'name' => 'Гражданская ответственность',
                'code' => 'sum_go',
                'type' => 'range',
                'min_value' => 0,
                'max_value' => 200000,
                'default_value' => 200000,
                'required_for_calc' => true,
                'sort_order' => 4,
                'risks' => ['Причинение вреда жизни, здоровью и/или имуществу третьих лиц'],
            ],
            [
                'name' => 'Воздействие электроэнергии',
                'code' => 'electricity',
                'type' => 'flag',
                'min_value' => null,
                'max_value' => null,
                'default_value' => 0,
                'required_for_calc' => false,
                'sort_order' => 5,
                'risks' => ['Воздействие электроэнергии'],
            ],
            [
                'name' => 'Замена ключей',
                'code' => 'exp_keys',
                'type' => 'set',
                'min_value' => null,
                'max_value' => null,
                'default_value' => 0,
                'set_values' => [0, 5000, 10000],
                'required_for_calc' => false,
                'sort_order' => 6,
                'risks' => ['Замена ключей'],
            ],
            [
                'name' => 'Аренда помещения',
                'code' => 'exp_rent',
                'type' => 'set',
                'min_value' => null,
                'max_value' => null,
                'default_value' => 0,
                'set_values' => [0, 100000],
                'required_for_calc' => false,
                'sort_order' => 7,
                'risks' => ['Аренда помещения'],
            ],
            [
                'name' => 'Транспортировка',
                'code' => 'exp_transport',
                'type' => 'set',
                'min_value' => null,
                'max_value' => null,
                'default_value' => 0,
                'set_values' => [0, 10000],
                'required_for_calc' => false,
                'sort_order' => 8,
                'risks' => ['Транспортировка'],
            ],
            [
                'name' => 'Досрочное возвращение',
                'code' => 'exp_return',
                'type' => 'set',
                'min_value' => null,
                'max_value' => null,
                'default_value' => 0,
                'set_values' => [0, 20000],
                'required_for_calc' => false,
                'sort_order' => 9,
                'risks' => ['Досрочное возвращение'],
            ],
            [
                'name' => 'Аренда (коэффициент)',
                'code' => 'k_rent',
                'type' => 'flag',
                'min_value' => null,
                'max_value' => null,
                'default_value' => 0,
                'required_for_calc' => false,
                'sort_order' => 10,
                'risks' => [],
            ],
        ];

        foreach ($coverages as $c) {
            ProductCoverage::create(array_merge($c, ['product_id' => $product->id]));
        }

        // ─── Группы полей ─────────────────────────────────────────────────
        $group1 = ProductFieldGroup::create([
            'product_id' => $product->id,
            'name' => 'Об объекте страхования',
            'code' => 'object_info',
            'description' => 'Информация о квартире',
            'sort_order' => 1,
        ]);

        $group2 = ProductFieldGroup::create([
            'product_id' => $product->id,
            'name' => 'Страхователь',
            'code' => 'policyholder',
            'description' => 'Данные страхователя',
            'sort_order' => 2,
        ]);

        $group3 = ProductFieldGroup::create([
            'product_id' => $product->id,
            'name' => 'Дополнительно',
            'code' => 'additional',
            'description' => 'Промокод и комментарии',
            'sort_order' => 3,
        ]);

        // ─── Поля ─────────────────────────────────────────────────────────
        $fields = [
            // Группа 1: Объект
            ['group_id' => $group1->id, 'name' => 'Тип помещения', 'code' => 'property_type', 'type' => 'select', 'required' => true, 'sort_order' => 1, 'options' => [['value' => 'apartment', 'label' => 'Квартира']]],
            ['group_id' => $group1->id, 'name' => 'Тип перекрытия', 'code' => 'cover_type', 'type' => 'select', 'required' => true, 'sort_order' => 2, 'options' => [['value' => 'no_wood', 'label' => 'Деревянные перекрытия отсутствуют']]],
            ['group_id' => $group1->id, 'name' => 'Адрес помещения', 'code' => 'property_address', 'type' => 'address', 'required' => true, 'sort_order' => 3, 'hint' => 'Москва, Питер, Лен и Моск области'],
            ['group_id' => $group1->id, 'name' => 'Совпадает с адресом регистрации?', 'code' => 'address_same', 'type' => 'linked_field', 'required' => false, 'sort_order' => 4, 'linked_to' => 'policyholder_address'],
            ['group_id' => $group1->id, 'name' => 'Сдаётся в долгосрочную аренду?', 'code' => 'is_rent', 'type' => 'checkbox', 'required' => false, 'sort_order' => 5],
            ['group_id' => $group1->id, 'name' => 'Общая площадь, кв.м.', 'code' => 'area', 'type' => 'number', 'required' => true, 'sort_order' => 6],
            ['group_id' => $group1->id, 'name' => 'Дата начала страхования', 'code' => 'start_date', 'type' => 'date', 'required' => true, 'sort_order' => 7],

            // Группа 2: Страхователь
            ['group_id' => $group2->id, 'name' => 'Фамилия', 'code' => 'policyholder_last_name', 'type' => 'text', 'required' => true, 'sort_order' => 1, 'regex' => '/^[А-Яа-яЁё\s]+$/', 'error_message' => 'Только кириллица'],
            ['group_id' => $group2->id, 'name' => 'Имя', 'code' => 'policyholder_first_name', 'type' => 'text', 'required' => true, 'sort_order' => 2, 'regex' => '/^[А-Яа-яЁё\s]+$/'],
            ['group_id' => $group2->id, 'name' => 'Отчество', 'code' => 'policyholder_middle_name', 'type' => 'text', 'required' => false, 'sort_order' => 3, 'regex' => '/^[А-Яа-яЁё\s]+$/'],
            ['group_id' => $group2->id, 'name' => 'Адрес регистрации', 'code' => 'policyholder_address', 'type' => 'address', 'required' => true, 'sort_order' => 4],
            ['group_id' => $group2->id, 'name' => 'Дата рождения', 'code' => 'policyholder_birthdate', 'type' => 'birthdate', 'required' => true, 'sort_order' => 5],
            ['group_id' => $group2->id, 'name' => 'Серия паспорта', 'code' => 'policyholder_passport_series', 'type' => 'passport_series', 'required' => true, 'sort_order' => 6, 'mask' => '99 99'],
            ['group_id' => $group2->id, 'name' => 'Номер паспорта', 'code' => 'policyholder_passport_number', 'type' => 'passport_number', 'required' => true, 'sort_order' => 7, 'mask' => '999999'],
            ['group_id' => $group2->id, 'name' => 'Дата выдачи паспорта', 'code' => 'policyholder_passport_date', 'type' => 'date', 'required' => true, 'sort_order' => 8],
            ['group_id' => $group2->id, 'name' => 'Кем выдан паспорт', 'code' => 'policyholder_passport_issued_by', 'type' => 'textarea', 'required' => true, 'sort_order' => 9],
            ['group_id' => $group2->id, 'name' => 'Код подразделения', 'code' => 'policyholder_passport_code', 'type' => 'text', 'required' => true, 'sort_order' => 10, 'mask' => '999-999'],
            ['group_id' => $group2->id, 'name' => 'Номер телефона', 'code' => 'policyholder_phone', 'type' => 'phone', 'required' => true, 'sort_order' => 11],
            ['group_id' => $group2->id, 'name' => 'E-mail', 'code' => 'policyholder_email', 'type' => 'email', 'required' => true, 'sort_order' => 12],

            // Группа 3: Дополнительно
            ['group_id' => $group3->id, 'name' => 'Промокод', 'code' => 'promo_code', 'type' => 'text', 'required' => false, 'sort_order' => 1],
            ['group_id' => $group3->id, 'name' => 'Комментарии', 'code' => 'comment', 'type' => 'textarea', 'required' => false, 'sort_order' => 2],
        ];

        foreach ($fields as $f) {
            ProductField::create(array_merge($f, ['product_id' => $product->id]));
        }

        // ─── Ограничения на заказ ─────────────────────────────────────────
        // Возраст >= 18
        $r1 = ProductRestriction::create([
            'product_id' => $product->id,
            'category' => 'order',
            'action' => 'block',
            'error_message' => 'Возраст застрахованного не может быть меньше 18 лет',
            'logic' => 'and',
            'sort_order' => 1,
        ]);
        ProductRestrictionCondition::create([
            'restriction_id' => $r1->id,
            'field_code' => 'age',
            'operator' => '<',
            'value' => 18,
            'sort_order' => 1,
        ]);

        // Дата начала >= сегодня + 7 дней (проверяется в UI)

        // Адрес должен содержать номер квартиры
        $r2 = ProductRestriction::create([
            'product_id' => $product->id,
            'category' => 'order',
            'action' => 'block',
            'error_message' => 'Адрес должен указываться с указанием номера квартиры',
            'logic' => 'and',
            'sort_order' => 2,
        ]);
        ProductRestrictionCondition::create([
            'restriction_id' => $r2->id,
            'field_code' => 'property_address',
            'operator' => 'regex',
            'value' => '/кв/i',
            'sort_order' => 1,
        ]);

        // ─── Соглашения ───────────────────────────────────────────────────
        $agreements = [
            ['text' => 'Настоящим подтверждаю, что с правилами страхования, условиями страхования (проект договора) и Ключевым Информационным Документом ознакомлен и согласен.', 'required' => true],
            ['text' => 'Даю согласие на обработку персональных данных.', 'required' => true],
            ['text' => 'Настоящим подтверждаю декларацию.', 'required' => true],
            ['text' => 'Являюсь публичным должностным лицом/иностранным публичным должностным лицом.', 'required' => false],
            ['text' => 'Являюсь владельцем платежной карты, с которой производится оплата страховой премии.', 'required' => true],
            ['text' => 'Я согласен получать информацию о страховых продуктах АО СК "Турикум".', 'required' => false],
        ];

        foreach ($agreements as $idx => $a) {
            ProductAgreement::create(array_merge($a, ['product_id' => $product->id, 'sort_order' => $idx + 1]));
        }

        $this->command->info('✅ Продукт «Страху.Нет» создан в новом конструкторе');
    }

    /**
     * Продукт 2: Страхование квартиры «Новосел» (Ипотека)
     */
    private function seedNovosel(): void
    {
        $numerator = Numerator::where('name', 'Ипотека Новосел')->first();
        if (!$numerator) {
            $numerator = Numerator::create([
                'name' => 'Ипотека Новосел',
                'prefix' => 'NOV',
                'include_year' => true,
                'year_digits' => 2,
                'counter_length' => 7,
                'start_value' => 1,
                'reset_period' => 'yearly'
            ]);
        }

        Product::where('code', 'mortgage')->delete();

        $product = Product::create([
            'code' => 'mortgage',
            'name' => 'Страхование квартиры «Новосел»',
            'marketing_name' => 'Новосел',
            'description' => 'Ипотечное страхование: жизнь, имущество, титул',
            'numerator_id' => $numerator->id,
            'calculator_class' => 'App\\Services\\ProductCalculators\\MortgageCalculator', // Оставляем старый для сложных расчётов
            'config_json' => [],
            'formula_expression' => null, // Сложная формула с перестрахованием — используем MortgageCalculator
            'formula_variables' => [],
            'currency' => 'RUB',
            'is_active' => true,
            'status' => 'published',
            'current_version' => 1,
            'period_start_days' => 7,
            'period_end_value' => 1,
            'period_end_unit' => 'years',
            'send_email' => true,
            'email_field' => 'policyholder_email',
            'allow_edit_start_date' => true,
            'approval_emails' => 'underwriting@thuricum.ru',
        ]);

        // ─── Покрытия ─────────────────────────────────────────────────────
        $coverages = [
            [
                'name' => 'Несчастный случай (Жизнь)',
                'code' => 'sum_life',
                'type' => 'range',
                'min_value' => 0,
                'max_value' => 45000000,
                'default_value' => 0,
                'required_for_calc' => true,
                'sort_order' => 1,
                'risks' => ['Смерть в результате несчастного случая или болезни', 'Установление I или II группы инвалидности'],
            ],
            [
                'name' => 'Имущество (Конструктивные элементы)',
                'code' => 'sum_property',
                'type' => 'range',
                'min_value' => 0,
                'max_value' => 45000000,
                'default_value' => 0,
                'required_for_calc' => true,
                'sort_order' => 2,
                'risks' => ['Пожар', 'Взрыв', 'Удар молнии', 'Стихийные бедствия', 'Залив', 'Падение предметов', 'Противоправные действия третьих лиц', 'Наезд ТС', 'Конструктивные дефекты'],
            ],
            [
                'name' => 'Титул',
                'code' => 'sum_title',
                'type' => 'range',
                'min_value' => 0,
                'max_value' => 45000000,
                'default_value' => 0,
                'required_for_calc' => false,
                'sort_order' => 3,
                'risks' => ['Полная или частичная утрата права собственности', 'Ограничение права собственности'],
            ],
        ];

        foreach ($coverages as $c) {
            ProductCoverage::create(array_merge($c, ['product_id' => $product->id]));
        }

        // ─── Группы полей ─────────────────────────────────────────────────
        $group1 = ProductFieldGroup::create([
            'product_id' => $product->id,
            'name' => 'Об объекте страхования',
            'code' => 'object_info',
            'sort_order' => 1,
        ]);

        $group2 = ProductFieldGroup::create([
            'product_id' => $product->id,
            'name' => 'Страхователь',
            'code' => 'policyholder',
            'sort_order' => 2,
        ]);

        // ─── Поля (выборка ключевых) ──────────────────────────────────────
        $fields = [
            ['group_id' => $group1->id, 'name' => 'Банк', 'code' => 'bank', 'type' => 'select', 'required' => true, 'sort_order' => 1, 'options' => [['value' => 'sber', 'label' => 'Сбербанк'], ['value' => 'vtb', 'label' => 'ВТБ'], ['value' => 'alfa', 'label' => 'Альфабанк']]],
            ['group_id' => $group1->id, 'name' => 'Остаток суммы задолженности', 'code' => 'osg', 'type' => 'number', 'required' => true, 'sort_order' => 2],
            ['group_id' => $group1->id, 'name' => 'Тип помещения', 'code' => 'room_type', 'type' => 'select', 'required' => true, 'sort_order' => 3, 'options' => [['value' => 'house', 'label' => 'Дом'], ['value' => 'apartment', 'label' => 'Квартира'], ['value' => 'non_residential', 'label' => 'Нежилое помещение']]],
            ['group_id' => $group1->id, 'name' => 'Тип перекрытия', 'code' => 'cover_type', 'type' => 'select', 'required' => true, 'sort_order' => 4, 'options' => [['value' => 'stone', 'label' => 'Каменный'], ['value' => 'mixed', 'label' => 'Смешанный'], ['value' => 'wood', 'label' => 'Деревянный']]],
            ['group_id' => $group1->id, 'name' => 'Адрес помещения', 'code' => 'property_address', 'type' => 'address', 'required' => true, 'sort_order' => 5],
            ['group_id' => $group1->id, 'name' => 'Общая площадь, кв.м.', 'code' => 'area', 'type' => 'number', 'required' => true, 'sort_order' => 6],
            ['group_id' => $group1->id, 'name' => 'Возраст дома', 'code' => 'house_age', 'type' => 'number', 'required' => true, 'sort_order' => 7],
            ['group_id' => $group1->id, 'name' => 'Дата рождения застрахованного', 'code' => 'birthdate', 'type' => 'birthdate', 'required' => true, 'sort_order' => 8],
            ['group_id' => $group1->id, 'name' => 'Пол', 'code' => 'sex', 'type' => 'select', 'required' => true, 'sort_order' => 9, 'options' => [['value' => 'male', 'label' => 'Мужской'], ['value' => 'female', 'label' => 'Женский']]],
            ['group_id' => $group1->id, 'name' => 'Экстремальный спорт?', 'code' => 'extreme_sport', 'type' => 'checkbox', 'required' => false, 'sort_order' => 10],
            ['group_id' => $group1->id, 'name' => 'Травмоопасная деятельность?', 'code' => 'dangerous_activity', 'type' => 'select', 'required' => false, 'sort_order' => 11, 'options' => [['value' => 'no', 'label' => 'Нерисковая'], ['value' => 'yes', 'label' => 'Опасная']]],
            ['group_id' => $group1->id, 'name' => 'Дата начала страхования', 'code' => 'start_date', 'type' => 'date', 'required' => true, 'sort_order' => 12],

            ['group_id' => $group2->id, 'name' => 'Фамилия', 'code' => 'policyholder_last_name', 'type' => 'text', 'required' => true, 'sort_order' => 1],
            ['group_id' => $group2->id, 'name' => 'Имя', 'code' => 'policyholder_first_name', 'type' => 'text', 'required' => true, 'sort_order' => 2],
            ['group_id' => $group2->id, 'name' => 'Отчество', 'code' => 'policyholder_middle_name', 'type' => 'text', 'required' => false, 'sort_order' => 3],
            ['group_id' => $group2->id, 'name' => 'Адрес регистрации', 'code' => 'policyholder_address', 'type' => 'address', 'required' => true, 'sort_order' => 4],
            ['group_id' => $group2->id, 'name' => 'Дата рождения', 'code' => 'policyholder_birthdate', 'type' => 'birthdate', 'required' => true, 'sort_order' => 5],
            ['group_id' => $group2->id, 'name' => 'Серия паспорта', 'code' => 'policyholder_passport_series', 'type' => 'passport_series', 'required' => true, 'sort_order' => 6],
            ['group_id' => $group2->id, 'name' => 'Номер паспорта', 'code' => 'policyholder_passport_number', 'type' => 'passport_number', 'required' => true, 'sort_order' => 7],
            ['group_id' => $group2->id, 'name' => 'Дата выдачи паспорта', 'code' => 'policyholder_passport_date', 'type' => 'date', 'required' => true, 'sort_order' => 8],
            ['group_id' => $group2->id, 'name' => 'Кем выдан', 'code' => 'policyholder_passport_issued_by', 'type' => 'textarea', 'required' => true, 'sort_order' => 9],
            ['group_id' => $group2->id, 'name' => 'Код подразделения', 'code' => 'policyholder_passport_code', 'type' => 'text', 'required' => true, 'sort_order' => 10],
            ['group_id' => $group2->id, 'name' => 'Телефон', 'code' => 'policyholder_phone', 'type' => 'phone', 'required' => true, 'sort_order' => 11],
            ['group_id' => $group2->id, 'name' => 'E-mail', 'code' => 'policyholder_email', 'type' => 'email', 'required' => true, 'sort_order' => 12],
        ];

        foreach ($fields as $f) {
            ProductField::create(array_merge($f, ['product_id' => $product->id]));
        }

        // ─── Андеррайтинг ─────────────────────────────────────────────────
        // Жизнь > 10 млн
        $r1 = ProductRestriction::create([
            'product_id' => $product->id,
            'category' => 'underwriting',
            'action' => 'approval',
            'error_message' => 'Страховая сумма по Жизни свыше 10 млн требует согласования',
            'logic' => 'and',
            'sort_order' => 1,
        ]);
        ProductRestrictionCondition::create([
            'restriction_id' => $r1->id,
            'field_code' => 'sum_life',
            'operator' => '>',
            'value' => 10000000,
            'sort_order' => 1,
        ]);

        // Имущество > 10 млн
        $r2 = ProductRestriction::create([
            'product_id' => $product->id,
            'category' => 'underwriting',
            'action' => 'approval',
            'error_message' => 'Страховая сумма по Имуществу свыше 10 млн требует согласования',
            'logic' => 'and',
            'sort_order' => 2,
        ]);
        ProductRestrictionCondition::create([
            'restriction_id' => $r2->id,
            'field_code' => 'sum_property',
            'operator' => '>',
            'value' => 10000000,
            'sort_order' => 1,
        ]);

        // Титул > 0 (всегда на согласование)
        $r3 = ProductRestriction::create([
            'product_id' => $product->id,
            'category' => 'underwriting',
            'action' => 'approval',
            'error_message' => 'Титульное страхование всегда требует согласования',
            'logic' => 'and',
            'sort_order' => 3,
        ]);
        ProductRestrictionCondition::create([
            'restriction_id' => $r3->id,
            'field_code' => 'sum_title',
            'operator' => '>',
            'value' => 0,
            'sort_order' => 1,
        ]);

        // Возраст 18-60
        $r4 = ProductRestriction::create([
            'product_id' => $product->id,
            'category' => 'order',
            'action' => 'block',
            'error_message' => 'Возраст застрахованного должен быть от 18 до 60 лет',
            'logic' => 'or',
            'sort_order' => 1,
        ]);
        ProductRestrictionCondition::create(['restriction_id' => $r4->id, 'field_code' => 'age', 'operator' => '<', 'value' => 18, 'sort_order' => 1]);
        ProductRestrictionCondition::create(['restriction_id' => $r4->id, 'field_code' => 'age', 'operator' => '>', 'value' => 60, 'sort_order' => 2]);

        // ─── Декларации ───────────────────────────────────────────────────
        ProductDeclaration::create([
            'product_id' => $product->id,
            'name' => 'Декларация по НС',
            'text' => 'Я не являюсь безработным, пенсионером, домохозяйкой; не являюсь судимым; не являюсь госпитализированным; деятельность не связана с работой на высоте, на воде, под водой; не состою на службе в ВС РФ, Полиции, ФСБ; не занимаюсь экстремальными видами спорта; не являюсь инвалидом; не страдаю алкоголизмом, наркоманией; не страдаю хроническими заболеваниями и т.д.',
            'is_active' => true,
            'required' => true,
            'sort_order' => 1,
        ]);

        ProductDeclaration::create([
            'product_id' => $product->id,
            'name' => 'Декларация по имуществу',
            'text' => 'Объект страхования является квартирой/апартаментами в доме не старше 71 года; конструктивные материалы стен негорючие; не расположена в деревянном строении; отсутствует несогласованная перепланировка; не ведутся ремонтные работы; не находится в аварийном состоянии и т.д.',
            'is_active' => true,
            'required' => true,
            'sort_order' => 2,
        ]);

        $this->command->info('✅ Продукт «Новосел» создан в новом конструкторе');
    }
}
