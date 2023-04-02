<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->isProduction()) {
            //
        } else {
//            \App\Models\User::factory(10)->create();
            $this->call([
                LocationSeeder::class,
            ]);
        }
    }
}
