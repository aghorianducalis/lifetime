<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Event;
use App\Models\ResourceType;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::factory()
            ->withEvents(Event::factory()->count(100)->create()->pluck('id')->toArray())
            ->withResourceTypes(ResourceType::factory()->count(100)->create()->pluck('id')->toArray())
            ->count(10)
            ->create();
    }
}
