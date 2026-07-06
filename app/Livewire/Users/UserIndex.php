<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;
    public $search = '';
    public $roleFilter = '';

    public function render()
    {
        $users = User::with('intermediary','roles')
            ->when($this->search, fn($q)=>$q->where('name','like',"%{$this->search}%")->orWhere('email','like',"%{$this->search}%"))
            ->when($this->roleFilter, fn($q)=>$q->role($this->roleFilter))
            ->orderByDesc('id')->paginate(20);
        return view('livewire.users.index', compact('users'));
    }
}
