<?php

namespace Database\Factories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => Hash::make(Str::random(10)),
            'remember_token'    => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function withEvents(array $eventIds): static
    {
        return $this->afterCreating(function (User $user) use ($eventIds) {
            $this->getRepository()->attachEvents($user, $eventIds);
        });
    }

    public function withCoordinates(array $coordinateIds): static
    {
        return $this->afterCreating(function (User $user) use ($coordinateIds) {
            $this->getRepository()->attachCoordinates($user, $coordinateIds);
        });
    }

    public function withResources(array $resourceIds): static
    {
        return $this->afterCreating(function (User $user) use ($resourceIds) {
            $this->getRepository()->attachResources($user, $resourceIds);
        });
    }

    public function withResourceTypes(array $resourceTypeIds): static
    {
        return $this->afterCreating(function (User $user) use ($resourceTypeIds) {
            $this->getRepository()->attachResourceTypes($user, $resourceTypeIds);
        });
    }

    protected function getRepository(): UserRepositoryInterface
    {
        return app(UserRepositoryInterface::class);
    }
}
