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
        Schema::create('booking_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('from_status', [
                'pending',
                'waiting_payment',
                'paid',
                'confirmed',
                'cancelled',
                'completed'
            ])->nullable();
            $table->enum('to_status', [
                'pending',
                'waiting_payment',
                'paid',
                'confirmed',
                'cancelled',
                'completed'
            ]);
            $table->string('action');
            $table->text('notes')->nullable(); 
            $table->ipAddress('ip_address')->nullable();
            $table->index(['booking_id', 'created_at']);
            $table->index(['user_id', 'action']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_logs');
    }
};
