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
    protected $color;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking, string $type,string $message, string $targetUserType = 'owner')
    {
        $this->booking = $booking;
        $this->type = $type;
        $this->targetUserType = $targetUserType;
        $this->message = $message;
        $this->color = $this->determineColor($booking->status);
        
    }

    protected function determineColor($type): string
    {
        return match($type) {
            'pending' => '#3B82F6',
            'confirmed' => '#10B981',      
            'cancelled' => '#EF4444',     
            'waiting_payment' => '#F59E0B',       
            'paid' => '#8B5CF6',     
            'completed' => '#06B6D4',     
            default => '#6B7280'           
        };
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
            'color' => $this->color,
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
