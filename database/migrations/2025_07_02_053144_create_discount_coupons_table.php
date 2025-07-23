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
        Schema::create('discount_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->text('description')->nullable();
            $table->enum('discount_type', ['percentage', 'fixed_amount']);
            $table->decimal('discount_value', 10, 2);
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->integer('max_uses')->nullable();
            $table->integer('current_uses')->default(0);
            $table->boolean('is_active')->default(true);
            $table->enum('applies_to', ['all_establishments', 'specific_establishments', 'specific_types']);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_coupons');
    }
};
