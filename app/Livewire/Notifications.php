<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Notifications\GeneralNotification;
use App\Services\AdminLoggerService;
use App\Services\FirebaseService;

class Notifications extends Component
{
    public $title;
    public $description;
    public $user_type;
    private $firebaseService;

    public function __construct()
    {
        $this->firebaseService = new FirebaseService();
    }

    public function sendNotification()
    {

        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'user_type' => 'required|in:owner,user,All',
        ],[
            'title.required' => 'العنوان مطلوب',
            'description.required' => 'الوصف مطلوب',
            'user_type.required' => 'نوع المستخدم مطلوب',
            'user_type.in' => 'نوع المستخدم غير صالح',
        ]
    
    );

        try {
            if ($this->user_type == 'owner') {
                $usersToSend = User::where('user_type', 'owner')->whereNotNull('device_token')->get();
            } elseif ($this->user_type == 'user') {
                $usersToSend = User::where('user_type', 'user')->whereNotNull('device_token')->get();
            } elseif ($this->user_type == 'All') {
                $usersToSend = User::whereNotNull('device_token')->get();
            } else {
                $this->dispatch('show-toast', [
                    'type' => 'success',
                    'message' => 'تمت إرسال الإشعار بنجاح'
                ]);
                $this->reset(['title', 'description', 'user_type']);
                return;
            }

            foreach ($usersToSend as $user) {
                try {
                    $this->firebaseService->sendNotification(
                        $user->device_token,
                        $this->title,
                        $this->description,
                        []
                    );
                } catch (\Exception $e) {
                    \Log::error("Failed to send notification to user: {$user->id}. Error: {$e->getMessage()}");
                }

                $user->notify(new GeneralNotification($this->title, $this->description));
            }
            AdminLoggerService::log(
                'إرسال إشعار عام',
                'Notification',
                "تم إرسال إشعار عام بعنوان: {$this->title} إلى نوع المستخدم: {$this->user_type}"
            );

            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'تمت إرسال الإشعار بنجاح'
            ]);
            $this->reset(['title', 'description', 'user_type']);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'حدث خطأ غير متوقع: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}