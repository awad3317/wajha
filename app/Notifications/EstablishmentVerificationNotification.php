<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Establishment;

class EstablishmentVerificationNotification extends Notification
{
    use Queueable;
    protected $establishment;
    protected $isVerified;

    /**
     * Create a new notification instance.
     */
        public function __construct(Establishment $establishment, bool $isVerified)
    {
        $this->establishment = $establishment;
        $this->isVerified = $isVerified;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable)
    {
        $title = $this->isVerified 
            ? "تهانينا! تم توثيق منشأتك" 
            : "إشعار بإلغاء توثيق المنشأة";
            
        $body = $this->isVerified
            ? "لقد تم توثيق منشأتك '{$this->establishment->name}' بنجاح في منصتنا."
            : "نأسف لإبلاغك، تم إلغاء توثيق منشأتك '{$this->establishment->name}'. لمزيد من التفاصيل، يرجى مراجعة الدعم الفني.";

        return [
            'type' => $this->isVerified ? 'establishment_verified' : 'establishment_unverified',
            'establishment_id' => (string)$this->establishment->id,
            'establishment_name' => $this->establishment->name,
            'title' => $title,
            'message' => $body,
            'user_id' => (string)$notifiable->id,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
