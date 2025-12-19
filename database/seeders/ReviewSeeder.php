<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Review;
use App\Models\User;
use App\Models\CarRental;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $rentals = CarRental::all();

        $comments = [
            'Pelayanan sangat memuaskan.',
            'Mobil bersih dan nyaman.',
            'Harga sesuai dengan kualitas.',
            'Respon admin cepat.',
            'Pengalaman sewa yang menyenangkan.',
            'Mobil sedikit kotor, tapi masih oke.',
            null,
        ];

        foreach (range(1, 40) as $i) {
            Review::create([
                'user_id' => $users->random()->id,
                'car_rental_id' => $rentals->random()->id,
                'rating' => rand(3, 5),
                'comment' => $comments[array_rand($comments)],
            ]);
        }
    }
}
