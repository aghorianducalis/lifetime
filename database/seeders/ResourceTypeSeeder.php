<?php

declare(strict_types=1);

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
        $resourceTypes = ResourceType::factory()
            ->count(100)
            ->create();
    }
}
