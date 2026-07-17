<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class UserProfile extends Component
{
    public ?User $profileUser = null;

    public function mount($id = null)
    {
        $this->profileUser = User::with('intermediary', 'roles', 'policies.product')
            ->findOrFail($id ?? auth()->id());
    }

    public function render()
    {
        $user = $this->profileUser;
        $stats = [
            'policies_total' => $user->policies()->count(),
            'policies_issued' => $user->policies()->where('status', 'issued')->count(),
            'policies_draft' => $user->policies()->where('status', 'draft')->count(),
            'policies_pending' => $user->policies()->where('status', 'pending_approval')->count(),
        ];

        $recentPolicies = $user->policies()
            ->with('product')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('livewire.users.profile', compact('user', 'stats', 'recentPolicies'));
    }
}
