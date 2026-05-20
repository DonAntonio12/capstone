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
        Schema::create('sensor_readings', function (Blueprint $table) {
            $table->id();
            $table->decimal('n', 8, 2)->comment('Nitrogen level');
            $table->decimal('p', 8, 2)->comment('Phosphorus level');
            $table->decimal('k', 8, 2)->comment('Potassium level');
            $table->decimal('ph', 4, 2)->comment('pH level');
            $table->string('soil_type')->nullable()->comment('Determined soil type');
            $table->text('recommendations')->nullable()->comment('Fertilizer recommendations');
            $table->integer('readings_count')->nullable()->comment('Number of readings averaged');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_readings');
    }
}; 