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
        Schema::create('soil_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->float('n');
            $table->float('p');
            $table->float('k');
            $table->float('ph');
            $table->float('ideal_n')->nullable();
            $table->float('ideal_p')->nullable();
            $table->float('ideal_k')->nullable();
            $table->float('ideal_ph')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('soil_type')->nullable();
            $table->text('recommendation')->nullable();
            $table->text('prediction')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soil_tests');
    }
};
