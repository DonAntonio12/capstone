<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('collection_session_id');
            $table->decimal('predicted_nitrogen', 8, 2);
            $table->decimal('predicted_phosphorus', 8, 2);
            $table->decimal('predicted_potassium', 8, 2);
            $table->timestamp('prediction_date')->useCurrent();
            $table->timestamp('prediction_for_date')->nullable();
            $table->decimal('confidence_score', 5, 2); // Model's confidence in prediction
            $table->json('model_parameters')->nullable(); // Store model parameters used
            $table->text('recommendations')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('predictions');
    }
}; 