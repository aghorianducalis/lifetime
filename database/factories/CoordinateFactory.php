<?php

namespace Database\Factories;

use App\Models\Coordinate;
use App\Models\Event;
use App\Models\Location;
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
            'x'           => fake()->randomFloat(),
            'y'           => fake()->randomFloat(),
            'z'           => fake()->randomFloat(),
            't'           => fake()->dateTime(),
            'event_id'    => Event::factory(),
            'location_id' => Location::factory(),
        ];
    }
}
