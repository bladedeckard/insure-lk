<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Models\Intermediary;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserForm extends Component
{
    public ?User $user = null;
    public $name = '';
    public $email = '';
    public $intermediary_id = null;
    public $is_active = true;
    public $roles = [];

    public function mount($id = null)
    {
        if ($id) {
            $this->user = User::findOrFail($id);
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->intermediary_id = $this->user->intermediary_id;
            $this->is_active = $this->user->is_active;
            $this->roles = $this->user->roles->pluck('name')->toArray();
        }
    }

    public function save()
    {
        $this->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users,email,'.($this->user->id ?? ''),
            'intermediary_id'=>'nullable|exists:intermediaries,id',
            'roles'=>'array'
        ]);

        $isNew = !$this->user;
        $u = $this->user ?? new User();
        $u->name = $this->name;
        $u->email = $this->email;
        $u->intermediary_id = $this->intermediary_id ?: null;
        $u->is_active = $this->is_active;
        if ($isNew) {
            $password = Str::random(12);
            $u->password = Hash::make($password);
            $u->save();
            $u->syncRoles($this->roles);
            // Mail::raw("Ваш пароль: $password", fn($m)=>$m->to($u->email)->subject('Доступ в ЛК'));
            session()->flash('password_plain', $password);
        } else {
            $u->save();
            $u->syncRoles($this->roles);
        }
        session()->flash('ok','Сохранено');
        return redirect()->route('users.index');
    }

    public function resetPassword()
    {
        if(!$this->user) return;
        $password = Str::random(12);
        $this->user->password = Hash::make($password);
        $this->user->save();
        session()->flash('password_plain', $password);
    }

    public function deleteUser()
    {
        if($this->user) { $this->user->delete(); return redirect()->route('users.index'); }
    }

    public function render()
    {
        return view('livewire.users.form', [
            'intermediaries' => Intermediary::active()->orderBy('name')->get(),
            'allRoles' => Role::orderBy('id')->get(['name','title_ru']),
        ]);
    }
}
