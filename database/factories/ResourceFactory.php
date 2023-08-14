<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Resource;
use App\Models\ResourceType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resource>
 */
class ResourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Resource::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount'           => fake()->randomFloat(),
            'event_id'         => Event::factory(),
            'resource_type_id' => ResourceType::factory(),
        ];
    }
}
