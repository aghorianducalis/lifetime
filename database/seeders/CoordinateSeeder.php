<?php

namespace Database\Seeders;

use App\Models\Coordinate;
use Illuminate\Database\Seeder;

class CoordinateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coordinates = Coordinate::factory()->count(100)->create();
    }
}
