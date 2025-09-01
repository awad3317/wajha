<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\AdminLoggerService;


class Users extends Component
{
    use WithPagination;

    public $search = '';
    public $userType = '';
    public $bannedStatus = '';
    protected $queryString = ['search', 'userType', 'bannedStatus'];
    public function toggleBan($userId)

    {
        $user = User::find($userId);
        if ($user) {
            $user->is_banned = !$user->is_banned;
            $user->save();
            if ($user->is_banned) {
                $user->tokens()->delete();
                AdminLoggerService::log(
                    'ban_user',
                    $user,
                    'تم حظر المستخدم',
                );
            }
            else{
                AdminLoggerService::log(
                    'unban_user',
                    $user,
                    'تم فك حظر المستخدم',
                );
            }
            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => $user->is_banned ? 'تم حظر المستخدم بنجاح' : 'تم فك حظر المستخدم بنجاح'
            ]);
        }
    }
    public function updating($field)
    {
        $this->resetPage();
    }
    public $editingUserId = null;
    public $editName, $editPhone, $editType;

    public function editUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $this->editingUserId = $user->id;
            $this->editType = $user->user_type;
        }
         
        
    }

    public function updateUser()
    {
        $user = User::find($this->editingUserId);
        if ($user) {
    
            $user->user_type = $this->editType;
            $user->save();

            $this->editingUserId = null;
                $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'تم تعديل المستخدم بنجاح'
            ]);
        }
    }

    public function cancelEdit()
    {
        $this->editingUserId = null;
    }

    public function render()
    {

        $query = User::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->userType !== '') {
            $query->where('user_type', $this->userType);
        }

        if ($this->bannedStatus !== '') {
            $query->where('is_banned', $this->bannedStatus);
        }

        $users = $query->orderBy('id', 'desc')->paginate(10);
        return view('livewire.users', compact('users'));
    }
}