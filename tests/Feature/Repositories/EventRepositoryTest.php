<?php

namespace Tests\Feature\Repositories;

use App\Models\Coordinate;
use App\Models\Event;
use App\Models\Resource;
use App\Models\User;
use App\Repositories\EventRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Repositories\EventRepository
 */
class EventRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected EventRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(EventRepository::class);
    }

    /**
     * @test
     * @covers ::find
     */
    public function test_find()
    {
        /** @var Event $event */
        $event = Event::factory()->create();

        $foundEvent = $this->repository->find($event->id);

        $this->assertInstanceOf(Event::class, $foundEvent);
        $this->assertEquals($event->title, $foundEvent->title);
        $this->assertEquals($event->description, $foundEvent->description);
    }

    /**
     * @test
     * @covers ::find
     */
    public function test_find_non_existing_resource()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->repository->find($this->getRandomUuid());
    }

    /**
     * @test
     * @covers ::matching
     */
    public function test_get_list()
    {
        Event::factory(5)->create();

        $events = $this->repository->matching();

        $this->assertCount(5, $events);
    }

    /**
     * @test
     * @covers ::create
     */
    public function test_create()
    {
        /** @var Event $event */
        $event = Event::factory()->make();

        /** @var Event $createdEvent */
        $createdEvent = $this->repository->create($event->toArray());

        $this->assertInstanceOf(Event::class, $createdEvent);
        $this->assertEquals($event->title, $createdEvent->title);
        $this->assertEquals($event->description, $createdEvent->description);
        $this->assertDatabaseHas($event->getTable(), [
            'title'       => $event->title,
            'description' => $event->description,
        ]);
    }

    /**
     * @test
     * @covers ::attachUsers
     */
    public function test_attach_users()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Event $event */
        $event = Event::factory()->create();

        $this->repository->attachUsers($event, [$user->id]);

        $this->assertCount(1, $user->events);
        $this->assertCount(1, $event->users);
        $this->assertEquals($event->id, $user->events()->first()->id);
        $this->assertEquals($user->id, $event->users()->first()->id);
        $this->assertDatabaseHas($event->users()->getTable(), [
            'event_id' => $event->id,
            'user_id'  => $user->id,
        ]);
    }

    /**
     * @test
     * @covers ::detachUsers
     */
    public function test_detach_users()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Event $event */
        $event = Event::factory()->withUsers([$user->id])->create();

        $detachedUsersCount = $this->repository->detachUsers($event, [$user->id]);

        $this->assertEquals(1, $detachedUsersCount);
        $this->assertCount(0, $user->events);
        $this->assertCount(0, $event->users);
        $this->assertDatabaseMissing($event->users()->getTable(), [
            'event_id' => $event->id,
            'user_id'  => $user->id,
        ]);
    }

    /**
     * @test
     * @covers ::attachCoordinates
     */
    public function test_attach_coordinates()
    {
        /** @var Event $event */
        $event = Event::factory()->create();

        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->create();

        $this->repository->attachCoordinates($event, [$coordinate->id]);

        $this->assertCount(1, $event->coordinates);
        $this->assertCount(1, $coordinate->events);
        $this->assertEquals($coordinate->id, $event->coordinates()->first()->id);
        $this->assertEquals($event->id, $coordinate->events()->first()->id);
        $this->assertDatabaseHas($coordinate->events()->getTable(), [
            'event_id'      => $event->id,
            'coordinate_id' => $coordinate->id,
        ]);
    }

    /**
     * @test
     * @covers ::detachCoordinates
     */
    public function test_detach_coordinates()
    {
        /** @var Event $event */
        $event = Event::factory()->create();

        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->withEvents([$event->id])->create();

        $detachedCoordinatesCount = $this->repository->detachCoordinates($event, [$coordinate->id]);

        $this->assertEquals(1, $detachedCoordinatesCount);
        $this->assertCount(0, $coordinate->events);
        $this->assertCount(0, $event->coordinates);
        $this->assertDatabaseMissing($event->coordinates()->getTable(), [
            'event_id'      => $event->id,
            'coordinate_id' => $coordinate->id,
        ]);
    }

    /**
     * @test
     * @covers ::attachResources
     */
    public function test_attach_resources()
    {
        /** @var Resource $resource */
        $resource = Resource::factory()->create();

        /** @var Event $event */
        $event = Event::factory()->create();

        $this->repository->attachResources($event, [$resource->id]);

        $this->assertCount(1, $resource->events);
        $this->assertCount(1, $event->resources);
        $this->assertEquals($event->id, $resource->events()->first()->id);
        $this->assertEquals($resource->id, $event->resources()->first()->id);
        $this->assertDatabaseHas($event->resources()->getTable(), [
            'event_id'    => $event->id,
            'resource_id' => $resource->id,
        ]);
    }

    /**
     * @test
     * @covers ::detachResources
     */
    public function test_detach_resources()
    {
        /** @var Resource $resource */
        $resource = Resource::factory()->create();

        /** @var Event $event */
        $event = Event::factory()->withResources([$resource->id])->create();

        $detachedResourcesCount = $this->repository->detachResources($event, [$resource->id]);

        $this->assertEquals(1, $detachedResourcesCount);
        $this->assertCount(0, $resource->events);
        $this->assertCount(0, $event->resources);
        $this->assertDatabaseMissing($event->resources()->getTable(), [
            'event_id'    => $event->id,
            'resource_id' => $resource->id,
        ]);
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_update_non_existing_resource()
    {
        $newData = Event::factory()->make()->toArray();

        $this->expectException(ModelNotFoundException::class);
        $this->repository->update($newData, $this->getRandomUuid());
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_update()
    {
        /** @var Event $event */
        $event = Event::factory()->create();
        /** @var Event $newEvent */
        $newEvent = Event::factory()->make();

        /** @var Event $updatedEvent */
        $updatedEvent = $this->repository->update($newEvent->toArray(), $event->id);

        $this->assertInstanceOf(Event::class, $updatedEvent);
        $this->assertEquals($newEvent->title, $updatedEvent->title);
        $this->assertEquals($newEvent->description, $updatedEvent->description);
        $this->assertDatabaseHas($event->getTable(), [
            'id'          => $event->id,
            'title'       => $newEvent->title,
            'description' => $newEvent->description,
        ]);
    }

    /**
     * @test
     * @covers ::delete
     */
    public function test_delete()
    {
        $event = Event::factory()->create();

        $result = $this->repository->delete($event->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing($event->getTable(), [
            'id' => $event->id
        ]);
    }
}
