<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('establishment_id')->constrained('establishments');
            $table->foreignId('price_package_id')->constrained('price_packages');
            $table->dateTime('booking_date');
            $table->string('payment_receipt_image')->nullable();
            $table->foreignId('coupon_id')->nullable()->constrained('discount_coupons');
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->enum('status', [
                'pending',
                'waiting_payment',
                'paid',
                'confirmed',
                'cancelled',
                'completed'
            ])->default('pending');
            $table->timestamps();
            $table->index(['user_id', 'establishment_id', 'booking_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
