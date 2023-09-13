<?php

namespace Database\Factories;

use App\Models\Resource;
use App\Models\ResourceType;
use App\Repositories\Interfaces\ResourceRepositoryInterface;
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
            'amount'           => fake()->randomFloat(10, 0, Resource::MAX_AMOUNT),
            'resource_type_id' => ResourceType::factory(),
        ];
    }

    public function withEvents(array $eventIds): static
    {
        return $this->afterCreating(function (Resource $resource) use ($eventIds) {
            $this->getRepository()->attachEvents($resource, $eventIds);
        });
    }

    public function withUsers(array $userIds): static
    {
        return $this->afterCreating(function (Resource $resource) use ($userIds) {
            $this->getRepository()->attachUsers($resource, $userIds);
        });
    }

    protected function getRepository(): ResourceRepositoryInterface
    {
        return app(ResourceRepositoryInterface::class);
    }
}
