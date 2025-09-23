<?php

namespace App\Notifications;

use App\Models\booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BookingModifiedNotification extends Notification
{
    use Queueable;

    protected $booking;
    protected $oldDate;
    protected $newDate;
    protected $modifierType; 
    protected $modifierName;

    /**
     * Create a new notification instance.
     */
    public function __construct(booking $booking, Carbon $oldDate, Carbon $newDate, string $modifierType, string $modifierName = '')
    {
        $this->booking = $booking;
        $this->oldDate = $oldDate;
        $this->newDate = $newDate;
        $this->modifierType = $modifierType;
        $this->modifierName = $modifierName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
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

    public function toDatabase($notifiable)
    {
        return [
            'booking_id' => $this->booking->id,
            'establishment_id' => $this->booking->establishment->id,
            'establishment_name' => $this->booking->establishment->name,
            'old_booking_date' => $this->oldDate->format('Y-m-d H:i'),
            'new_booking_date' => $this->newDate->format('Y-m-d H:i'),
            'modifier_type' => $this->modifierType,
            'modifier_name' => $this->modifierName,
            'type' => 'booking_modified',
            'color' => '#F59E0B',
            'message' => $this->getMessage(),
            'target_user_type' => $this->getTargetUserType($notifiable),
        ];

    }

    protected function getMessage(): string
    {
        $establishmentName = $this->booking->establishment->name;
        $oldDateFormatted = $this->oldDate->format('Y-m-d H:i');
        $newDateFormatted = $this->newDate->format('Y-m-d H:i');

        if ($this->modifierType === 'owner') {
            return "قام {$establishmentName} بتعديل موعد حجزك من {$oldDateFormatted} إلى {$newDateFormatted}";
        } else {
            return "قام العميل {$this->modifierName} بتعديل موعد الحجز في {$establishmentName} من {$oldDateFormatted} إلى {$newDateFormatted}";
        }
    }

    protected function getTargetUserType($notifiable): string
    {
        return $this->modifierType === 'owner' ? 'customer' : 'owner';
    }
}
