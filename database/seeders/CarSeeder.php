<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = DB::table('users')->pluck('id')->all();
        $tagIds = DB::table('tags')->pluck('id')->all();

        for ($i = 0; $i < 250; $i++) {
            $sold = fake()->boolean(25);

            $carId = DB::table('cars')->insertGetId([
                'user_id' => fake()->randomElement($userIds),
                'license_plate' => strtoupper(fake()->bothify('##???#')),
                'brand' => fake()->randomElement(['Audi', 'BMW', 'Mercedes', 'Volkswagen', 'Toyota']),
                'model' => fake()->randomElement(['A4', 'X5', 'C Klasse', 'Golf', 'Yaris']),
                'price' => fake()->numberBetween(1500, 45000),
                'mileage' => fake()->numberBetween(5000, 280000),
                'seats' => fake()->randomElement([2, 4, 5, 7]),
                'doors' => fake()->randomElement([2, 3, 4, 5]),
                'production_year' => fake()->numberBetween(2003, 2025),
                'weight' => fake()->numberBetween(800, 2200),
                'color' => fake()->randomElement(['zwart', 'wit', 'blauw', 'grijs', 'rood']),
                'sold_at' => $sold ? now()->subDays(fake()->numberBetween(1, 90)) : null,
                'views' => fake()->numberBetween(0, 300),
                'created_at' => now()->subDays(fake()->numberBetween(0, 365)),
                'updated_at' => now(),
            ]);

            $randomTagIds = fake()->randomElements($tagIds, fake()->numberBetween(1, 4));

            foreach ($randomTagIds as $tagId) {
                DB::table('car_tag')->insert([
                    'car_id' => $carId,
                    'tag_id' => $tagId,
                ]);
            }
        }
    }
}
