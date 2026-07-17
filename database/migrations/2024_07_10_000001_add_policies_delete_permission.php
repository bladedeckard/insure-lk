<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        // Добавляем право на удаление полисов
        Permission::updateOrCreate(
            ['name' => 'policies.delete', 'guard_name' => 'web'],
            ['title_ru' => 'Удаление полисов', 'group' => 'Полисы']
        );

        // Назначаем право ролям admin и chief_manager
        $admin = Role::where('name', 'admin')->first();
        if ($admin && !$admin->hasPermissionTo('policies.delete')) {
            $admin->givePermissionTo('policies.delete');
        }

        $chiefManager = Role::where('name', 'chief_manager')->first();
        if ($chiefManager && !$chiefManager->hasPermissionTo('policies.delete')) {
            $chiefManager->givePermissionTo('policies.delete');
        }
    }

    public function down(): void
    {
        Permission::where('name', 'policies.delete')->delete();
    }
};
