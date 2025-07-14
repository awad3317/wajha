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
            $table->foreignId('establishment_id')->constrained('establishments');
            $table->date('unavailable_date');
            $table->timestamps();
            
            $table->unique(['establishment_id', 'unavailable_date'], 'est_unavail_unique');
            
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
