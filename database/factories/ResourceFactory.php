<?php

namespace Database\Factories;

use App\Models\Resource;
use App\Models\ResourceType;
use App\Models\User;
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
            'amount'           => fake()->randomFloat(10, 0, 999999.9999),
            'resource_type_id' => ResourceType::factory(),
        ];
    }

    public function forUser(User $user = null): static
    {
        $user = $user ?: User::factory()->create();

        return $this->afterCreating(function (Resource $resource) use ($user) {
            $resource->users()->attach($user);
        });
    }
}
