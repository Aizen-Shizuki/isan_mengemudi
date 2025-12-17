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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_rental_id')->constrained('car_rentals')->cascadeOnDelete();
            $table->foreignId('car_type_id')->constrained('car_types')->restrictOnDelete();
            $table->foreignId('series_year_id')->constrained('series_years')->cascadeOnDelete();
            $table->string('car_name');
            $table->integer('car_capacity');
            $table->integer('stock');
            $table->text('car_image')->nullable();
            $table->enum('car_condition', [
                'SANGAT BAIK',
                'BAIK',
                'KURANG BAIK',
            ]);
            $table->decimal('price_per_days');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
