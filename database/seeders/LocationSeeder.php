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
        $titles = [
            'home',
        ];

        foreach ($titles as $title) {
            $location = Location::query()->create([
                'title'         => $title,
                'description'   => '',
            ]);
        }
    }
}