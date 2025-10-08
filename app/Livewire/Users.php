<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\AdminLoggerService;
use Illuminate\Support\Facades\Hash;

class Users extends Component
{
    use WithPagination;
    public $search = '';
    public $userType = '';
    public $bannedStatus = '';
    protected $queryString = ['search', 'userType', 'bannedStatus'];

    public $showAddForm = false;
    public $newName, $newPhone, $newPassword;
    public $newCountry = 'YE';
    public $countryCodes = [
        'YE' => '967',
        'SA' => '966',
        'EG' => '20',
        'AE' => '971',
    ];


    public function storeOwner()
    {
        $rules = [
            'newName'     => 'required|string|min:3',
            'newPhone'    => 'required|string|unique:users,phone',
            'newPassword' => 'required|string|min:6',
        ];

        $messages = [
            'newName.required' => 'يرجى إدخال اسم صاحب المنشاة',
            'newPhone.required' => 'يرجى إدخال رقم الجوال',
            'newPassword.required' => 'يرجى إدخال كلمة المرور',
            'newPhone.regex' => 'رقم الجوال غير صالح',
            'newPhone.unique' => 'رقم الجوال مستخدم بالفعل',
        ];

        switch ($this->newCountry) {
            case 'YE': 
                $rules['newPhone'] .= '|regex:/^[0-9]{9}$/';
                $messages['newPhone.regex'] = 'رقم اليمن يجب أن يتكون من 9 أرقام';
                break;

            case 'SA':
                $rules['newPhone'] .= '|regex:/^[0-9]{9}$/';
                $messages['newPhone.regex'] = 'رقم السعودية يجب أن يتكون من 9 أرقام';
                break;

            case 'EG':
                $rules['newPhone'] .= '|regex:/^[0-9]{10}$/';
                $messages['newPhone.regex'] = 'رقم مصر يجب أن يتكون من 10 أرقام';
                break;

            case 'AE': 
                $rules['newPhone'] .= '|regex:/^[0-9]{9}$/';
                $messages['newPhone.regex'] = 'رقم الإمارات يجب أن يتكون من 9 أرقام';
                break;
        }

        $this->validate($rules, $messages);


        $user = User::create([
            'name' => $this->newName,
            'phone' => $this->countryCodes[$this->newCountry] . $this->newPhone,
            'password' => Hash::make($this->newPassword),
            'user_type' => 'owner',
            'phone_verified_at' => now(),
            'is_banned' => 0,
        ]);

        AdminLoggerService::log(
            'إضافة مستخدم',
            $user->name,
            'تمت إضافة صاحب منشأة جديد'
        );

        // إعادة تعيين الحقول
        $this->reset(['newName', 'newPhone', 'newPassword', 'showAddForm']);

        // رسالة نجاح
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تمت إضافة صاحب منشأة بنجاح'
        ]);
    }


    public function toggleBan($userId)

    {
        $user = User::find($userId);
        if ($user) {
            $user->is_banned = !$user->is_banned;
            $user->save();
            if ($user->is_banned) {
                $user->tokens()->delete();
                AdminLoggerService::log(
                    'تم حضر المستخدم',
                    $user->name,
                    'تم حظر المستخدم',
                );
            } else {
                AdminLoggerService::log(
                    'تم فك حضر المستخدم',
                    $user->name,
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
            $user->tokens()->delete();
            AdminLoggerService::log('تعديل المستخدم', 'User', "تعديل المستخدم: {$user->name}");

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