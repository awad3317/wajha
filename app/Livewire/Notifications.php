<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Services\FirebaseService;

class Notifications extends Component
{
    public $title;
    public $description;
    public $user_type;
    private $firebaseService;
    
    public function __construct() {
        $this->firebaseService = new FirebaseService();
    }

    public function sendNotification()
    {
        
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'user_type' => 'required|in:owner,user',
        ]);

        try {
            if ($this->user_type == 'owner') {
                $usersToSend = User::where('user_type', 'owner')->whereNotNull('device_token')->get();
            } elseif ($this->user_type == 'user') {
                $usersToSend = User::where('user_type', 'user')->whereNotNull('device_token')->get();
            } else {
                session()->flash('error', 'Invalid user type selected.');
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
            }

            session()->flash('success', 'تم إرسال الإشعار بنجاح!');
            $this->reset(['title', 'description', 'user_type']);

        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ غير متوقع: ' . $e->getMessage());
        }
    }
    
    public function render()
    {
        return view('livewire.notifications');
    }
}