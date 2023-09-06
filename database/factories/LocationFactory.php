<?php

namespace Database\Factories;

use App\Models\Coordinate;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Location::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'         => "Location #" . fake()->randomNumber() . " " . fake()->unique()->sentence(),
            'description'   => fake()->text(),
            'coordinate_id' => Coordinate::factory()->create(['t' => Carbon::parse(0)->toDateTimeString()]),
        ];
    }
}
