<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class RoleForm extends Component
{
    public ?Role $role = null;
    public $name = '';
    public $title_ru = '';
    public $description = '';
    public $perms = [];

    public function mount($id = null)
    {
        if ($id) {
            $this->role = Role::findOrFail($id);
            $this->name = $this->role->name;
            $this->title_ru = $this->role->title_ru;
            $this->description = $this->role->description;
            $this->perms = $this->role->permissions->pluck('name')->toArray();
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|alpha_dash|max:64|unique:roles,name,'.($this->role->id ?? ''),
            'title_ru' => 'required|string|max:255',
            'description' => 'nullable|string',
            'perms' => 'array',
        ];
    }

    public function save()
    {
        $this->validate();
        
        $data = [
            'name' => Str::lower($this->name),
            'guard_name' => 'web',
            'title_ru' => $this->title_ru,
            'description' => $this->description,
        ];

        if ($this->role) {
            $this->role->update($data);
            $r = $this->role;
        } else {
            $r = Role::create($data);
        }
        $r->syncPermissions($this->perms);
        
        session()->flash('ok', 'Роль сохранена');
        return redirect()->route('roles.index');
    }

    public function render()
    {
        $allPerms = Permission::orderBy('group')->orderBy('id')->get()->groupBy('group');
        return view('livewire.roles.form', [
            'allPerms' => $allPerms,
        ])->layout('components.layouts.app');
    }
}
