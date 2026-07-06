# Insure LK - Личный кабинет страховой компании

Laravel 11 + PHP 8.2 + Livewire 3 + Tailwind 3
MySQL 8.0 / MariaDB 10.11
spatie/laravel-permission

Реализовано:
1. Управление пользователями с ролями, поиском, фильтрацией
2. Роли и права: Администратор, Главный менеджер, Менеджер, Страховой агент
3. Посредники (Юр.лица/ИП/ФЛ) + интеграция DaData
4. Словари (универсальные)
5. Нумераторы полисов с автогенерацией: S380Z26000001
6. Конструктор страховых продуктов (JSON-схема)
7. 2 готовых продукта: 
   - «Страху.Нет» - Страхование квартиры (Имущество)
   - «Новосел» - Ипотека
   с полным расчётом премии по ТЗ
8. Выпуск полисов, генерация DOCX, отправка на email

---

## Установка в XAMPP (Windows)

### 1. Требования
- XAMPP 8.2+ (PHP 8.2, MySQL 8.0, Apache)
- Composer 2.7+

Скачать:
- XAMPP: https://www.apachefriends.org/
- Composer: https://getcomposer.org/download/

### 2. Развертывание

1. Скопируйте папку `insure-lk` в `C:\xampp\htdocs\insure-lk`

2. Откройте XAMPP Control Panel, запустите Apache и MySQL

3. Создайте БД:
   - Откройте http://localhost/phpmyadmin
   - Создать БД `insure_lk`, сравнение `utf8mb4_unicode_ci`

4. В командной строке:
```
cd C:\xampp\htdocs\insure-lk
copy .env.example .env
composer install
php artisan key:generate
```

5. Настройте `.env`:
```
APP_NAME="Insure LK"
APP_URL=http://localhost/insure-lk/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=insure_lk
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mail.ru
MAIL_PORT=465
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=noreply@thuricum.ru
MAIL_FROM_NAME="СК Турикум"

DADATA_API_KEY=ваш_ключ
DADATA_SECRET_KEY=ваш_секрет

YOOKASSA_SHOP_ID=
YOOKASSA_SECRET_KEY=
```

6. Миграции и сиды:
```
php artisan migrate
php artisan db:seed
```

Создастся пользователь:
- Email: admin@thuricum.ru
- Пароль: password

Роли:
- admin
- chief_manager
- manager
- agent

Посредники, нумераторы и 2 продукта уже засеяны.

7. Сборка фронтенда:
```
npm install
npm run build
```
Или для разработки: `npm run dev`

8. Права на папки (Linux) – в Windows не нужно.

9. Откройте: http://localhost/insure-lk/public/login

### Apache VirtualHost (рекомендуется)

`C:\xampp\apache\conf\extra\httpd-vhosts.conf`:
```
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/insure-lk/public"
    ServerName insure.test
    <Directory "C:/xampp/htdocs/insure-lk/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

`C:\Windows\System32\drivers\etc\hosts`:
```
127.0.0.1 insure.test
```

Тогда сайт: http://insure.test

---

## Структура

- `/app/Models` – User, Intermediary, Product, Policy, Numerator, Dictionary
- `/app/Services/ProductCalculators` – PropertyCalculator, MortgageCalculator
- `/app/Livewire` – все CRUD экраны
- `/app/Services/NumeratorService.php` – генерация номеров
- `/app/Services/DadataService.php`
- `/storage/app/templates` – DOCX шаблоны полисов

Продукты хранят схему в JSON (`products.config_json`):
```json
{
  "fields": [...],
  "risks": [...],
  "calculator": "property",
  "validation": {...}
}
```

Расчёт премии вынесен в `ProductCalculatorInterface`.

---

## DaData

В карточке Посредника введите ИНН → кнопка "Найти в DaData" → автозаполнение наименования.

Ключ получить: https://dadata.ru/

Если ключа нет – можно вводить вручную, поле dadata_json просто останется пустым.

---

## Нумераторы

Настройки → Нумераторы

Пример для «S380Z26000001»:
- Префикс: S380Z
- Включать год: Да (2 цифры)
- Длина счётчика: 6
- Начало: 1
- Сброс: каждый год

Генерация атомарная, с блокировкой строки.

---

## Продукты

Настройки → Страховые продукты

Конструктор:
- Общие условия
- Объекты / риски / страховые суммы
- Формула расчёта (выбирается класс-калькулятор)
- Поля виджета (JSON-форма)
- Валидация
- Шаблон DOCX

Готовые калькуляторы:
- `PropertyCalculator` – Страху.Нет
- `MortgageCalculator` – Новосел

Добавить свой: реализовать `ProductCalculatorInterface`, указать в config.

---

## Полисы

Полисы → Новый полис

1. Выбрать продукт
2. Заполнить виджет (динамическая форма по схеме продукта)
3. Расчёт премии – на лету
4. Сохранить черновик / отправить на согласование / выпустить
5. При выпуске: генерируется номер, рендерится DOCX, отправляется email

Агенты видят только полисы своего посредника (global scope в Policy).

---

## Права

Используется spatie/laravel-permission.

Разрешения: `users.view`, `users.manage`, `products.view`, `products.manage`, `policies.view`, `policies.create`, `policies.manage_all` и т.д.

Роли по ТЗ уже настроены в `RolesSeeder`.

Можно гибко назначать разрешения на группы в UI: Настройки → Роли.

---

## Почта и SMS

- Email отправка через Laravel Mail – настроено в `PolicyIssuedMail`
- SMS-подтверждение телефона – заглушка в `SmsService`, подключите вашего провайдера
- ЮKassa – заглушка в `YookassaService`

---

Дальнейшее развитие: добавить экспорт в ЭЛМ для ипотеки, промокоды, КИД-генерацию, журнал аудита.
