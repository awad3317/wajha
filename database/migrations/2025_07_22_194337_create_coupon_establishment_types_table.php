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
        Schema::create('coupon_establishment_types', function (Blueprint $table) {
           $table->id();
            $table->foreignId('coupon_id')->constrained('discount_coupons')->cascadeOnDelete();
            $table->foreignId('type_id')->constrained('establishment_types')->cascadeOnDelete();
            $table->timestamps();
            
            $table->unique(['coupon_id', 'type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_establishment_types');
    }
};
