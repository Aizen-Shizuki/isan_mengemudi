<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\SeriesYear;
use App\Models\CarType;
use App\Models\CarRental;
use App\Models\Car;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $conditions = ['SANGAT BAIK', 'BAIK', 'KURANG BAIK'];

        $seriesYears = SeriesYear::all();
        $carTypes = CarType::all();
        $rentals = CarRental::all();

        foreach (range(1, 40) as $i) {
            $sy = $seriesYears->random();
            $type = $carTypes->random();

            Car::create([
                'car_rental_id' => $rentals->random()->id,
                'car_type_id' => $type->id,
                'series_year_id' => $sy->id,
                'car_name' => $sy->colorSeries->series->series_name,
                'car_capacity' => rand(2, 8),
                'stock' => rand(1, 5),
                'car_image' => null,
                'car_condition' => $conditions[array_rand($conditions)],
                'price_per_days' => rand(300000, 1500000),
                'is_active' => true,
            ]);
        }
    }
}
