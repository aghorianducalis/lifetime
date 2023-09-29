<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ResourceType;
use App\Repositories\Interfaces\ResourceTypeRepositoryInterface;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ResourceType>
 */
class ResourceTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = ResourceType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'       => 'Resource type #' . fake()->randomNumber() . ' ' . fake()->sentence(),
            'description' => fake()->text(),
        ];
    }

    public function withUsers(array $userIds): static
    {
        return $this->afterCreating(function (ResourceType $resourceType) use ($userIds) {
            $this->getRepository()->attachUsers($resourceType, $userIds);
        });
    }

    protected function getRepository(): ResourceTypeRepositoryInterface
    {
        return app(ResourceTypeRepositoryInterface::class);
    }
}
