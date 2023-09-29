<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Models\Event;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\EventController
 */
class EventControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    /**
     * @test
     * @covers ::index
     */
    public function test_user_can_get_related_events()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Collection $events */
        $events = Event::factory(3)->withUsers([$user->id])->create();

        $response = $this->actingAs($user)->get(route('events.index'));

        $response->assertOk();
        $response->assertJsonCount($events->count(), 'data');
        $response->assertJsonStructure(['data' => [$this->getRequiredResponseFields()]]);
        $this->assertEquals($events->pluck('id')->toArray(), $response->json('data.*.id'));
    }

    /**
     * @test
     * @covers ::index
     */
    public function test_user_cannot_get_forbidden_events()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        Event::factory(3)->create();

        $response = $this->actingAs($user)->get(route('events.index'));

        $response->assertOk();
        $response->assertJsonCount(0, 'data');
    }

    /**
     * @test
     * @covers ::store
     */
    public function test_create_event()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        $eventData = Event::factory()->make()->toArray();

        $response = $this->actingAs($user)->postJson(route('events.store'), $eventData);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure(['data' => $this->getRequiredResponseFields()]);
        $response->assertJsonFragment($eventData);
        $this->assertDatabaseHas((new Event())->getTable(), ['id' => $response->json('data.id')]);
    }

    /**
     * @test
     * @covers ::show
     */
    public function test_user_can_get_related_event()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Event $event */
        $event = Event::factory()->withUsers([$user->id])->create();

        $response = $this->actingAs($user)->get(route('events.show', $event->id));

        $response->assertOk();
        $response->assertJsonStructure(['data' => $this->getRequiredResponseFields()]);
        $response->assertJsonFragment(['id' => $event->id]);
    }

    /**
     * @test
     * @covers ::show
     */
    public function test_user_cannot_get_forbidden_event()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Event $event */
        $event = Event::factory()->create();

        $response = $this->actingAs($user)->get(route('events.show', $event->id));
        $response->assertForbidden();
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_user_can_update_related_event()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Event $event */
        $event = Event::factory()->withUsers([$user->id])->create();
        $updatedData = Event::factory()->make()->only([
            'title',
            'description',
        ]);

        $response = $this->actingAs($user)->putJson(route('events.update', $event->id), $updatedData);

        $response->assertOk();
        $response->assertJsonStructure(['data' => $this->getRequiredResponseFields()]);
        $response->assertJsonFragment($updatedData);
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_user_cannot_update_forbidden_event()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Event $event */
        $event = Event::factory()->create();
        $updatedData = Event::factory()->make()->only([
            'title',
            'description',
        ]);

        $response = $this->actingAs($user)->putJson(route('events.update', $event->id), $updatedData);
        $response->assertForbidden();
    }

    /**
     * @test
     * @covers ::destroy
     */
    public function test_can_destroy_related_event()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Event $event */
        $event = Event::factory()->withUsers([$user->id])->create();

        $response = $this->actingAs($user)->deleteJson(route('events.destroy', $event->id));

        $response->assertOk();
        $response->assertExactJson(['result' => true]);

        $this->assertDatabaseMissing((new Event())->getTable(), ['id' => $event->id]);
    }

    /**
     * @test
     * @covers ::destroy
     */
    public function test_cannot_destroy_forbidden_event()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Event $event */
        $event = Event::factory()->create();

        $response = $this->actingAs($user)->deleteJson(route('events.destroy', $event->id));
        $response->assertForbidden();
    }

    private function getRequiredResponseFields(): array
    {
        return [
            'id',
            'title',
            'description',
            'created_at',
            'updated_at',
        ];
    }
}
