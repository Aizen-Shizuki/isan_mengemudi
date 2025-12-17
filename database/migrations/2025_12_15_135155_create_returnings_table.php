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
        Schema::create('returnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loaning_id')->constrained('loanings')->restrictOnDelete();
            $table->foreignId('charge_id')->constrained('charges')->restrictOnDelete();
            $table->date('return_date');
            $table->time('return_time');
            $table->text('proof_of_return');
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
        Schema::dropIfExists('returnings');
    }
};
