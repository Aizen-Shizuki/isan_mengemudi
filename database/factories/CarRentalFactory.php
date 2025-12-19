<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CarRental>
 */
class CarRentalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $province = Province::inRandomOrder()->first();
        $city = City::where('id', $province->id)->inRandomOrder()->first();
        $district = District::where('id', $city->id)->inRandomOrder()->first();
        $village = Village::where('id', $district->id)->inRandomOrder()->first();

        $openDaysOptions = [
            'Senin - Jumat',
            'Senin - Sabtu',
            'Senin - Minggu',
            'Selasa - Minggu',
            'Senin, Rabu, Jumat',
        ];

        $openTimes = ['07:00', '08:00', '09:00', '10:00'];
        $closeTimes = ['19:00', '20:00', '21:00', '22:00'];

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'province_id' => $province->id,
            'city_id' => $city->id,
            'district_id' => $district->id,
            'village_id' => $village->id,

            'car_rental_name' => 'Rental ' . fake()->company(),
            'description' => fake()->sentence(),
            'full_address' => fake()->address(),
            'image' => null,
            'phone_number' => fake()->numerify('08##########'),

            'npwp' => fake()->numerify('################'),
            'npwp_image' => 'npwp/sample.jpg',

            'latitude' => fake()->latitude(-11, 6),
            'longitude' => fake()->longitude(95, 141),

            'open_days' => fake()->randomElement($openDaysOptions),
            'open_time' => fake()->randomElement($openTimes),
            'close_time' => fake()->randomElement($closeTimes),

            'average_rating' => fake()->randomFloat(1, 3, 5),
        ];
    }
}
