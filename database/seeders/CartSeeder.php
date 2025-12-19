<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Cart;
use App\Models\User;
use App\Models\Car;


class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $cars = Car::all();

        foreach (range(1, 40) as $i) {
            $car = $cars->random();
            $quantity = rand(1, 3);

            Cart::create([
                'user_id' => $users->random()->id,
                'car_id' => $car->id,
                'quantity' => $quantity,
                'total_price' => $car->price_per_days * $quantity,
            ]);
        }
    }
}
