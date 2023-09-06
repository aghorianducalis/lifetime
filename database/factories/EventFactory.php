<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
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

    public function forUser(User $user = null): Factory
    {
        $user = $user ?: User::factory()->create();

        return $this->afterCreating(function (Event $event) use ($user) {
            $event->users()->attach($user);
        });
    }
}
