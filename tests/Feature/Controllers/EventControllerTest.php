<?php

namespace Tests\Feature\Controllers;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\EventController
 */
class EventControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     * @covers ::index
     */
    public function test_index()
    {
        Event::factory(3)->create();

        $response = $this->get(route('events.index'));

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
    }

    /**
     * @test
     * @covers ::store
     */
    public function test_create()
    {
        $eventData = Event::factory()->make()->toArray();

        $response = $this->postJson(route('events.store'), $eventData);

        $response->assertStatus(201)
            ->assertJsonFragment($eventData);
    }

    /**
     * @test
     * @covers ::show
     */
    public function test_show()
    {
        $event = Event::factory()->create();

        $response = $this->get(route('events.show', $event->id));

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $event->id]);
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_update()
    {
        $event = Event::factory()->create();
        $updatedData = Event::factory()->make()->only([
            'title',
            'description',
        ]);

        $response = $this->putJson(route('events.update', $event->id), $updatedData);

        $response->assertStatus(200)
            ->assertJsonFragment($updatedData);
    }

    /**
     * @test
     * @covers ::destroy
     */
    public function test_destroy()
    {
        $event = Event::factory()->create();

        $response = $this->deleteJson(route('events.destroy', $event->id));

        $response->assertStatus(200)
            ->assertExactJson(['result' => true]);

        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }
}
