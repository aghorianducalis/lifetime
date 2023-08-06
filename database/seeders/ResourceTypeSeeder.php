<?php

namespace Database\Seeders;

use App\Models\ResourceType;
use Illuminate\Database\Seeder;

class ResourceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titles = [
            'tea',
            'cigarettes',
            'hryvna',
        ];

        foreach ($titles as $title) {
            $resource = ResourceType::factory()->create([
                'title' => $title,
            ]);
        }
    }
}
