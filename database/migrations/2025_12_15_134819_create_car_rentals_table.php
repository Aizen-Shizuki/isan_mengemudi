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
        Schema::create('car_rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('province_id')->constrained('indonesia_provinces');
            $table->foreignId('city_id')->constrained('indonesia_cities');
            $table->foreignId('district_id')->constrained('indonesia_districts');
            $table->foreignId('village_id')->constrained('indonesia_villages');
            $table->string('car_rental_name');
            $table->text('description')->nullable();
            $table->text('full_address');
            $table->text('image')->nullable();
            $table->char('phone_number', 15)->nullable();
            $table->string('npwp', 20);
            $table->text('npwp_image');
            $table->text('latitude')->nullable();
            $table->text('longitude')->nullable();
            $table->string('open_days');
            $table->time('open_time');
            $table->time('close_time');
            $table->decimal('average_rating')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_rentals');
    }
};
