<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

use App\Models\Loaning;
use App\Models\Car;
use App\Models\User;

class LoaningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'PENDING',
            'APPROVED',
            'ON LOAN',
            'LATE RETURN',
            'REJECTED',
            'DONE',
        ];

        $conditions = [
            'SANGAT BAIK',
            'BAIK',
            'KURANG BAIK',
        ];

        $cars = Car::all();
        $users = User::all();

        foreach (range(1, 40) as $i) {

            $loanDate = Carbon::now()->subDays(rand(1, 30));
            $returnPlan = (clone $loanDate)->addDays(rand(1, 7));

            Loaning::create([
                'car_id' => $cars->random()->id,
                'user_id' => $users->random()->id,
                'tenant_ktp' => 'ktp_dummy_' . rand(1000000000000000, 9999999999999999) . '.jpg',

                'loan_date' => $loanDate->toDateString(),
                'loan_time' => Carbon::createFromTime(rand(7, 12), [0, 30][array_rand([0, 1])])->toTimeString(),

                'return_date_plan' => $returnPlan->toDateString(),
                'return_time_plan' => Carbon::createFromTime(rand(13, 20), [0, 30][array_rand([0, 1])])->toTimeString(),

                'status' => $statuses[array_rand($statuses)],
                'car_condition' => $conditions[array_rand($conditions)],
            ]);
        }
    }
}
