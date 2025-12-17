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
        Schema::create('loanings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained('cars')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->text('tenant_ktp');
            $table->date('loan_date');
            $table->time('loan_time');
            $table->date('return_date_plan');
            $table->time('return_time_plan');
            $table->enum('status', [
                'PENDING',
                'APPROVED',
                'ON LOAN',
                'LATE RETURN',
                'REJECTED',
                'DONE',
            ])->default('PENDING');
            $table->enum('car_condition', [
                'SANGAT BAIK',
                'BAIK',
                'KURANG BAIK',
            ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loanings');
    }
};
