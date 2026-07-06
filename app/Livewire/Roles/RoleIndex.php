<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleIndex extends Component
{
    public function delete($id)
    {
        $role = Role::find($id);
        if ($role) {
            // Запрет удаления системных ролей (опционально)
            if (in_array($role->name, ['admin','chief_manager','manager','agent'])) {
                session()->flash('err', 'Системную роль удалять нельзя');
                return;
            }
            $role->delete();
            session()->flash('ok', 'Роль удалена');
        }
    }

    public function render()
    {
        $roles = Role::withCount('permissions')->orderBy('id')->get();
        return view('livewire.roles.index', compact('roles'))->layout('components.layouts.app');
    }
}
