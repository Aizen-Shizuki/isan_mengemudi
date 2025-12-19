<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CarType;

class CarTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['SEDAN', '4-5 Orang', 'Mobil sedan nyaman untuk perjalanan harian'],
            ['HATCHBACK', '4-5 Orang', 'Mobil kecil dan irit'],
            ['MPV', '6-8 Orang', 'Mobil keluarga'],
            ['SUV', '5-7 Orang', 'Mobil tangguh segala medan'],
            ['CROSSOVER', '5 Orang', 'Perpaduan SUV dan hatchback'],
            ['CITY CAR', '4 Orang', 'Mobil kompak perkotaan'],
            ['PICKUP', '2-3 Orang', 'Mobil angkut barang'],
            ['DOUBLE CABIN', '4-5 Orang', 'Pickup kabin ganda'],
            ['MINIBUS', '10-15 Orang', 'Mobil penumpang besar'],
            ['SPORT CAR', '2 Orang', 'Mobil performa tinggi'],
        ];

        foreach ($types as [$name, $capacity, $desc]) {
            CarType::create([
                'type_name' => $name,
                'type_capacity' => $capacity,
                'description' => $desc,
                'type_image' => null,
            ]);
        }
    }
}
