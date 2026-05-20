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
        Schema::table('soil_types', function (Blueprint $table) {
            $table->text('remarks')->nullable();
            $table->text('why_suitable')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soil_types', function (Blueprint $table) {
            $table->dropColumn('remarks');
            $table->dropColumn('why_suitable');
        });
    }
};
