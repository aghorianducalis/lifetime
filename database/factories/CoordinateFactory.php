<?php

namespace Database\Factories;

use App\Models\Coordinate;
use App\Repositories\Interfaces\CoordinateRepositoryInterface;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coordinate>
 */
class CoordinateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Coordinate::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'x' => fake()->randomFloat(14, Coordinate::MIN_VALUE, Coordinate::MAX_VALUE),
            'y' => fake()->randomFloat(14, Coordinate::MIN_VALUE, Coordinate::MAX_VALUE),
            'z' => fake()->randomFloat(14, Coordinate::MIN_VALUE, Coordinate::MAX_VALUE),
            't' => fake()->dateTime(),
        ];
    }

    public function withUsers(array $userIds): static
    {
        return $this->afterCreating(function (Coordinate $coordinate) use ($userIds) {
            $this->getRepository()->attachUsers($coordinate, $userIds);
        });
    }

    public function withEvents(array $eventIds): static
    {
        return $this->afterCreating(function (Coordinate $coordinate) use ($eventIds) {
            $this->getRepository()->attachEvents($coordinate, $eventIds);
        });
    }

    protected function getRepository(): CoordinateRepositoryInterface
    {
        return app(CoordinateRepositoryInterface::class);
    }
}
