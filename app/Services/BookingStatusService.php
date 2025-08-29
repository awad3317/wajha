<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingLog;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

class BookingStatusService
{
    public function markAsWaitingPayment(Booking $booking)
    {
        return DB::transaction(function () use ($booking) {
            if ($booking->status === 'cancelled') {
            throw new \Exception('لا يمكن تعديل الحجز الملغي');
        }
    
        $oldStatus = $booking->status;
        $booking->update([
            'status' => 'waiting_payment',
        ]);
        $this->logStatusChange($booking, $oldStatus, 'waiting_payment', 'markAsWaitingPayment');

        return $booking;
        });
    }

    public function markAsPaid(Booking $booking,$receiptImage)
    {
        return DB::transaction(function () use ($booking,$receiptImage) {

            if ($booking->status === 'cancelled') {
                throw new \Exception('لا يمكن تعديل الحجز الملغي');
            }
            $oldStatus = $booking->status;
            
            $booking->update([
                'status' => 'paid',
                'payment_receipt_image' => $receiptImage
            ]);
            $this->logStatusChange($booking, $oldStatus, 'paid', 'markAsPaid', ['receipt_image' => $receiptImage]);

            return $booking;
        });
    }

    public function confirmBooking(Booking $booking)
    {
        return DB::transaction(function () use ($booking) {
            if ($booking->status === 'cancelled') {
                throw new \Exception('لا يمكن تعديل الحجز الملغي');
            }
            if ($booking->status !== 'paid') {
                throw new \Exception('يمكن تأكيد الحجز فقط من الحالة المدفوعة');
            }
            $oldStatus = $booking->status;
            $booking->update(['status' => 'confirmed']);
            $this->logStatusChange($booking, $oldStatus, 'confirmed', 'confirmBooking');
        
            return $booking;
        });
    }

    public function cancelledBooking(Booking $booking,$cancellationReason = null)
    {
        return DB::transaction(function () use ($booking,$cancellationReason) {
            if ($booking->status === 'cancelled') {
                return $booking; 
            }
            $oldStatus = $booking->status;
            $booking->update(['status' => 'cancelled']);
            $this->logStatusChange($booking, $oldStatus, 'cancelled', 'cancelBooking', ['cancellation_reason' => $cancellationReason]);
            
            return $booking;
        });
    }

      public function revertBookingStatus(Booking $booking, $targetStatus)
    {
        return DB::transaction(function () use ($booking, $targetStatus) {
            if ($booking->status === 'cancelled') {
                throw new \Exception('لا يمكن تعديل الحجز الملغي');
            }

            $oldStatus = $booking->status;
            
            $allowedTransitions = [
                'waiting_payment' => ['pending'],
                'paid' => ['pending', 'waiting_payment'],
                'confirmed' => ['pending', 'waiting_payment', 'paid'],
                'cancelled' => ['pending', 'waiting_payment', 'paid', 'confirmed']
            ];

            if (!isset($allowedTransitions[$oldStatus]) || 
                !in_array($targetStatus, $allowedTransitions[$oldStatus])) {
                throw new \Exception('لا يمكن التراجع من الحالة الحالية إلى الحالة المستهدفة');
            }

            $booking->update(['status' => $targetStatus]);
            
            $this->logStatusChange(
                $booking, 
                $oldStatus, 
                $targetStatus, 
                'revertBookingStatus',
                ['reverted_from' => $oldStatus, 'reverted_to' => $targetStatus]
            );

            return $booking;
        });
    }

    protected function logStatusChange(Booking $booking, $fromStatus, $toStatus, $action, $notes = null) {
        $user = auth('sanctum')->user();
        BookingLog::create([
            'booking_id' => $booking->id,
            'user_id' => $user ? $user->id : null,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'action' => $action,
            'notes' => is_array($notes) ? json_encode($notes) : $notes,
            'ip_address' => Request::ip(),
        ]);
    }

    public function getBookingLogs($booking_id)
    {
        return BookingLog::where('booking_id', $booking_id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }


    
}