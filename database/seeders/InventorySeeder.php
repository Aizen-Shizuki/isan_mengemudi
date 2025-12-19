<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Color;
use App\Models\Series;
use App\Models\Year;
use App\Models\ColorSeries;
use App\Models\SeriesYear;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // COLORS
        $colors = collect([
            'Hitam',
            'Putih',
            'Silver',
            'Merah',
            'Biru',
            'Abu-abu',
        ])->map(fn ($name) => Color::create([
            'name' => $name,
            'description' => 'Warna ' . $name,
            'additional_price' => rand(0, 50000),
        ]));

        // SERIES
        $series = collect([
            'BMW Seri 3',
            'BMW X5',
            'Mercedes-Benz C-Class',
            'Mercedes-Benz E-Class',
            'Toyota Avanza',
            'Toyota Fortuner',
            'Toyota Land Cruiser 70 Series',
            'Honda Civic',
            'Honda CR-V',
            'Mitsubishi Pajero Sport',
            'Suzuki Ertiga',
            'Daihatsu Xenia',
            'Hyundai Palisade',
            'Mazda CX-5',
        ])->map(fn ($name) => Series::create([
            'series_name' => $name,
            'description' => 'Series ' . $name,
            'additional_price' => rand(0, 200000),
        ]));

        // YEARS
        $years = collect(range(2021, 2024))->map(fn ($year) => Year::create([
            'year' => $year,
            'description' => 'Produksi tahun ' . $year,
            'additional_price' => rand(0, 200000),
        ]));

        // COLOR_SERIES
        $colorSeriesList = [];

        foreach ($colors as $color) {
            foreach ($series->random(3) as $serie) {
                $colorSeriesList[] = ColorSeries::create([
                    'color_id' => $color->id,
                    'series_id' => $serie->id,
                    'stock' => rand(3, 15),
                ]);
            }
        }

        // SERIES_YEARS (> 40 DATA)
        foreach ($colorSeriesList as $cs) {
            foreach ($years->random(rand(2, 4)) as $year) {
                SeriesYear::create([
                    'color_series_id' => $cs->id,
                    'year_id' => $year->id,
                    'stock' => rand(1, 10),
                ]);
            }
        }
    }
}
