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
        Schema::create('establishment_specifications', function (Blueprint $table) {
            $table->id();
             $table->foreignId('establishment_id')->constrained('establishments')->onDelete('cascade');;
            $table->string('name',100);
            $table->string('icon',255)->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('establishment_specifications');
    }
};
