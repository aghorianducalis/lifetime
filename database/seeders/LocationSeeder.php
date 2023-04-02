<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'home',
        ];

        foreach ($names as $name) {
            $location = Location::query()->create([
                'name' => $name,
            ]);
        }
    }
}
