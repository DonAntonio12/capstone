<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('soil_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->json('thresholds')->nullable(); // e.g. {"N": [20,50], "P": [15,30], "K": [100,200], "pH": [6.0,7.0]}
            $table->json('best_crops')->nullable(); // e.g. ["Rice", "Corn"]
            $table->string('image_url')->nullable();
            $table->text('remarks')->nullable(); // admin-editable remarks
            $table->text('why_suitable')->nullable(); // admin-editable suitability reason
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('soil_types');
    }
}; 