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
        Schema::create('coupon_uses', function (Blueprint $table) {
           $table->id();
            $table->foreignId('coupon_id')->constrained('discount_coupons')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->timestamp('used_at')->useCurrent();
            $table->decimal('discount_amount', 10, 2);
            $table->timestamps();
            
            $table->unique(['coupon_id', 'booking_id']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_uses');
    }
};
