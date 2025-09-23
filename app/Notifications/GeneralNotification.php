<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class GeneralNotification extends Notification
{
    use Queueable;
    
    protected $title;
    protected $message;
    protected $color;

    public function __construct(string $title, string $message)
    {
        $this->title = $title;
        $this->message = $message;
        $this->color = '#6B7280'; 
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => 'general_notification',
            'target_user_type' => $notifiable->user_type,
            'color' => $this->color,
        ];
    }
}
