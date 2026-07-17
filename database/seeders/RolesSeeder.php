<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $perms = [
            // Пользователи
            ['name' => 'users.view', 'title_ru' => 'Просмотр пользователей', 'group' => 'Пользователи'],
            ['name' => 'users.manage', 'title_ru' => 'Управление пользователями', 'group' => 'Пользователи'],
            // Роли
            ['name' => 'roles.view', 'title_ru' => 'Просмотр ролей', 'group' => 'Роли'],
            ['name' => 'roles.manage', 'title_ru' => 'Управление ролями', 'group' => 'Роли'],
            // Посредники
            ['name' => 'intermediaries.view', 'title_ru' => 'Просмотр посредников', 'group' => 'Посредники'],
            ['name' => 'intermediaries.manage', 'title_ru' => 'Управление посредниками', 'group' => 'Посредники'],
            // Словари
            ['name' => 'dictionaries.view', 'title_ru' => 'Просмотр словарей', 'group' => 'Словари'],
            ['name' => 'dictionaries.manage', 'title_ru' => 'Управление словарями', 'group' => 'Словари'],
            // Нумераторы
            ['name' => 'numerators.view', 'title_ru' => 'Просмотр нумераторов', 'group' => 'Нумераторы'],
            ['name' => 'numerators.manage', 'title_ru' => 'Управление нумераторами', 'group' => 'Нумераторы'],
            // Продукты
            ['name' => 'products.view', 'title_ru' => 'Просмотр страховых продуктов', 'group' => 'Продукты'],
            ['name' => 'products.manage', 'title_ru' => 'Управление страховыми продуктами', 'group' => 'Продукты'],
            // Полисы
            ['name' => 'policies.view', 'title_ru' => 'Просмотр полисов', 'group' => 'Полисы'],
            ['name' => 'policies.create', 'title_ru' => 'Создание полисов', 'group' => 'Полисы'],
            ['name' => 'policies.manage_all', 'title_ru' => 'Управление всеми полисами', 'group' => 'Полисы'],
            ['name' => 'policies.delete', 'title_ru' => 'Удаление полисов', 'group' => 'Полисы'],
        ];

        foreach ($perms as $p) {
            Permission::updateOrCreate(
                ['name' => $p['name'], 'guard_name' => 'web'],
                ['title_ru' => $p['title_ru'], 'group' => $p['group']]
            );
        }

        $roles = [
            'admin' => [
                'title_ru' => 'Администратор системы',
                'description' => 'Полный доступ ко всей системе',
                'permissions' => Permission::all()->pluck('name')->toArray(),
            ],
            'chief_manager' => [
                'title_ru' => 'Главный менеджер страховой компании',
                'description' => 'Доступ к просмотру и созданию пользователей, страховых продуктов и полисов',
                'permissions' => ['users.view','users.manage','products.view','products.manage','policies.view','policies.create','policies.manage_all','policies.delete','intermediaries.view'],
            ],
            'manager' => [
                'title_ru' => 'Менеджер страховой компании',
                'description' => 'Доступ к просмотру и созданию полисов',
                'permissions' => ['products.view','policies.view','policies.create'],
            ],
            'agent' => [
                'title_ru' => 'Страховой агент',
                'description' => 'Доступ к просмотру и созданию полисов своего посредника',
                'permissions' => ['policies.view','policies.create'],
            ],
        ];

        foreach ($roles as $name => $data) {
            $role = Role::updateOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['title_ru' => $data['title_ru'], 'description' => $data['description']]
            );
            $role->syncPermissions($data['permissions']);
        }
    }
}
