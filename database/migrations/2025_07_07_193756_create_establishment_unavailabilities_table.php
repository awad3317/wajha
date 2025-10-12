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
        Schema::create('establishment_unavailabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('establishment_id')->constrained('establishments')->onDelete('cascade');
            $table->foreignId('price_package_id')->constrained('price_packages')->onDelete('cascade');
            $table->date('unavailable_date');
            $table->timestamps();
            
            $table->unique(['establishment_id', 'price_package_id', 'unavailable_date'], 'est_package_date_unique');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('establishment_unavailabilities');
    }
};
