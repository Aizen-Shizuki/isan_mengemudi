<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Charge;
use App\Models\Returning;

class ChargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chargeNames = [
            'Denda Keterlambatan',
            'Kerusakan Ringan',
            'Kerusakan Berat',
            'Biaya Pembersihan',
            'Ban Bocor',
            'Lampu Pecah',
        ];

        $returnings = Returning::all();

        foreach (range(1, 40) as $i) {
            $returning = $returnings->random();

            Charge::create([
                'returning_id' => $returning->id,
                'charge_name' => $chargeNames[array_rand($chargeNames)],
                'description' => 'Biaya tambahan akibat kondisi kendaraan saat pengembalian.',
                'image' => rand(0, 1) ? 'charge_' . uniqid() . '.jpg' : null,
                'additional_price' => rand(50000, 500000),
            ]);
        }
    }
}
