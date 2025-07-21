<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class BookingStatusService
{
    public function markAsWaitingPayment(Booking $booking)
    {
        return DB::transaction(function () use ($booking) {

            $booking->update([
                'status' => 'waiting_payment',
            ]);

            // يمكنك إضافة إرسال إشعار للعميل هنا
            return $booking;
        });
    }

    public function markAsPaid(Booking $booking,$receiptImage)
    {
        return DB::transaction(function () use ($booking,$receiptImage) {

            $booking->update([
                'status' => 'paid',
                'payment_receipt_image' => $receiptImage
            ]);
            
            // إرسال إشعار للمالك
            return $booking;
        });
    }

    public function confirmBooking(Booking $booking)
    {
        return DB::transaction(function () use ($booking) {
            if ($booking->status !== 'paid') {
                throw new \Exception('يمكن تأكيد الحجز فقط من الحالة المدفوعة');
            }

            $booking->update(['status' => 'confirmed']);
            
            // إرسال إشعار للعميل
            return $booking;
        });
    }

    public function cancelledBooking(Booking $booking)
    {
        return DB::transaction(function () use ($booking) {
            $booking->update(['status' => 'cancelled']);
            
            // إرسال إشعار للعميل
            return $booking;
        });
    }

    
}