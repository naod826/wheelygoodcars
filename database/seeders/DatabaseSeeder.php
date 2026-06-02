<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(150)->create();

        $names = [
            'Automaat', 'NAP', 'Dealer onderhouden', 'Trekhaak', 'Panoramadak',
            'Navigatie', 'Climate control', 'Cruise control', 'Parkeersensoren', 'LED',
            'Elektrische ramen', 'Leder', 'Sportief', 'Zuinig', 'Benzine',
            'Diesel', 'Hybride', 'Youngtimer', 'Schade vrij', '1e eigenaar',
        ];

        foreach ($names as $name) {
            Tag::firstOrCreate(['name' => $name]);
        }

        $this->call(CarSeeder::class);
    }
}
