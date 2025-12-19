<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

use App\Models\Returning;
use App\Models\Loaning;

class ReturningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $conditions = [
            'SANGAT BAIK',
            'BAIK',
            'KURANG BAIK',
        ];

        $loanings = Loaning::all();

        // Ambil maksimal 40 loaning (kalau loaning < 40, aman)
        $loanings->take(40)->each(function ($loaning) use ($conditions) {

            $returnDate = Carbon::parse($loaning->return_date_plan)
                ->addDays(rand(0, 3)); // bisa tepat waktu / telat

            Returning::create([
                'loaning_id' => $loaning->id,
                'return_date' => $returnDate->toDateString(),
                'return_time' => Carbon::createFromTime(rand(9, 20), [0, 30][array_rand([0, 1])])->toTimeString(),
                'proof_of_return' => 'proof_return_' . uniqid() . '.jpg',
                'car_condition' => $conditions[array_rand($conditions)],
            ]);
        });
    }
}
