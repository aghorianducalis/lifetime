<?php

namespace Database\Factories;

use App\Models\Event;
use App\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'       => 'Event #' . fake()->randomNumber() . ' ' . fake()->sentence(5),
            'description' => fake()->text(),
        ];
    }

    public function withCoordinates(array $coordinateIds): static
    {
        return $this->afterCreating(function (Event $event) use ($coordinateIds) {
            $this->getRepository()->attachCoordinates($event, $coordinateIds);
        });
    }

    public function withResources(array $resourceIds): static
    {
        return $this->afterCreating(function (Event $event) use ($resourceIds) {
            $this->getRepository()->attachResources($event, $resourceIds);
        });
    }

    public function withUsers(array $userIds): static
    {
        return $this->afterCreating(function (Event $event) use ($userIds) {
            $this->getRepository()->attachUsers($event, $userIds);
        });
    }

    protected function getRepository(): EventRepositoryInterface
    {
        return app(EventRepositoryInterface::class);
    }
}
