<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\booking;

class NewBookingNotification extends Notification
{
    use Queueable;

    protected $booking;
    protected $type;
    protected $message;
    protected $targetUserType;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking, string $type,string $message, string $targetUserType = 'owner')
    {
        $this->booking = $booking;
        $this->type = $type;
        $this->targetUserType = $targetUserType;
        $this->message = $message;
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
        return [
            'booking_id' => $this->booking->id,
            'establishment_id' => $this->booking->establishment->id,
            'establishment_name' => $this->booking->establishment->name,
            'booking_date' => $this->booking->booking_date->format('Y-m-d H:i'),
            'type' => $this->type,
            'target_user_type' => $this->targetUserType,
            'message' => $this->message,
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
