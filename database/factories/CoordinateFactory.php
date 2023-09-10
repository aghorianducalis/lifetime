<?php

namespace Database\Factories;

use App\Models\Coordinate;
use App\Models\Location;
use App\Models\User;
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

    public function withLocation(Location $location = null): static
    {
        $location = $location ?: Location::factory()->create();

        return $this->afterCreating(function (Coordinate $coordinate) use ($location) {
            $coordinate->location()->save($location);
            $coordinate->save();
        });
    }

    public function forUser(User $user = null): static
    {
        $user = $user ?: User::factory()->create();

        return $this->afterCreating(function (Coordinate $coordinate) use ($user) {
            $coordinate->users()->attach($user);
        });
    }
}
