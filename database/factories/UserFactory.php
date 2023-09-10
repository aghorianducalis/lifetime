<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\ResourceType;
use App\Models\User;
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

    public function withEvents(int $count, array $events = []): static
    {
        if (empty($events)) {
            $events = Event::factory()->count($count)->create();
        }

        return $this->afterCreating(function (User $user) use ($events) {
            $user->events()->sync($events);
        });
    }

    public function withResourceTypes(int $count, array $resourceTypes = []): static
    {
        if (empty($resourceTypes)) {
            $resourceTypes = ResourceType::factory()->count($count)->create();
        }

        return $this->afterCreating(function (User $user) use ($resourceTypes) {
            $user->resourceTypes()->sync($resourceTypes);
        });
    }
}
