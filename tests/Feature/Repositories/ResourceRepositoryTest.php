<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\Event;
use App\Models\Resource;
use App\Models\User;
use App\Repositories\ResourceRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Repositories\ResourceRepository
 */
class ResourceRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected ResourceRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(ResourceRepository::class);
    }

    /**
     * @test
     * @covers ::find
     */
    public function test_find()
    {
        /** @var Resource $resource */
        $resource = Resource::factory()->create();

        $foundResource = $this->repository->find($resource->id);

        $this->assertInstanceOf(Resource::class, $foundResource);
        $this->assertEquals($resource->amount, $foundResource->amount);
        $this->assertEquals($resource->resource_type_id, $foundResource->resource_type_id);
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
     * @covers ::findByUser
     */
    public function test_find_by_user()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Resource $resource */
        $resource = Resource::factory()->withUsers([$user->id])->create();

        $foundResources = $this->repository->findByUser($user->id);
        $this->assertCount(1, $foundResources);
        $foundResource = $foundResources->first();
        $this->assertEquals($resource->id, $foundResource->id);
        $this->assertEquals($user->id, $foundResource->users()->first()->id);
    }

    /**
     * @test
     * @covers ::matching
     */
    public function test_get_all()
    {
        Resource::factory(5)->create();

        $resources = $this->repository->matching();

        $this->assertCount(5, $resources);
    }

    /**
     * @test
     * @covers ::create
     */
    public function test_create()
    {
        /** @var Resource $resource */
        $resource = Resource::factory()->make();

        /** @var Resource $createdResource */
        $createdResource = $this->repository->create($resource->toArray());

        $this->assertInstanceOf(Resource::class, $createdResource);
        $this->assertEquals($resource->amount, $createdResource->amount);
        $this->assertEquals($resource->resource_type_id, $createdResource->resource_type_id);
        $this->assertDatabaseHas($resource->getTable(), [
            'amount'           => $resource->amount,
            'resource_type_id' => $resource->resource_type_id,
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

        /** @var Resource $resource */
        $resource = Resource::factory()->create();

        $this->repository->attachUsers($resource, [$user->id]);

        $this->assertCount(1, $user->resources);
        $this->assertCount(1, $resource->users);
        $this->assertEquals($resource->id, $user->resources()->first()->id);
        $this->assertEquals($user->id, $resource->users()->first()->id);
        $this->assertDatabaseHas($resource->users()->getTable(), [
            'resource_id' => $resource->id,
            'user_id'     => $user->id,
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

        /** @var Resource $resource */
        $resource = Resource::factory()->withUsers([$user->id])->create();

        $detachedUsersCount = $this->repository->detachUsers($resource, [$user->id]);

        $this->assertEquals(1, $detachedUsersCount);
        $this->assertCount(0, $user->resources);
        $this->assertCount(0, $resource->users);
        $this->assertDatabaseMissing($resource->users()->getTable(), [
            'resource_id' => $resource->id,
            'user_id'     => $user->id,
        ]);
    }

    /**
     * @test
     * @covers ::attachEvents
     */
    public function test_attach_events()
    {
        /** @var Event $event */
        $event = Event::factory()->create();

        /** @var Resource $resource */
        $resource = Resource::factory()->create();

        $this->repository->attachEvents($resource, [$event->id]);

        $this->assertCount(1, $event->resources);
        $this->assertCount(1, $resource->events);
        $this->assertEquals($resource->id, $event->resources()->first()->id);
        $this->assertEquals($event->id, $resource->events()->first()->id);
        $this->assertDatabaseHas($resource->events()->getTable(), [
            'resource_id' => $resource->id,
            'event_id'    => $event->id,
        ]);
    }

    /**
     * @test
     * @covers ::detachEvents
     */
    public function test_detach_events()
    {
        /** @var Event $event */
        $event = Event::factory()->create();

        /** @var Resource $resource */
        $resource = Resource::factory()->withEvents([$event->id])->create();

        $detachedEventsCount = $this->repository->detachEvents($resource, [$event->id]);

        $this->assertEquals(1, $detachedEventsCount);
        $this->assertCount(0, $event->resources);
        $this->assertCount(0, $resource->events);
        $this->assertDatabaseMissing($resource->events()->getTable(), [
            'resource_id' => $resource->id,
            'event_id'    => $event->id,
        ]);
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_update_non_existing_resource()
    {
        $newData = Resource::factory()->make()->toArray();

        $this->expectException(ModelNotFoundException::class);
        $this->repository->update($newData, $this->getRandomUuid());
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_update()
    {
        /** @var Resource $resource */
        $resource = Resource::factory()->create();
        /** @var Resource $newResource */
        $newResource = Resource::factory()->make();

        /** @var Resource $updatedResource */
        $updatedResource = $this->repository->update($newResource->toArray(), $resource->id);

        $this->assertInstanceOf(Resource::class, $updatedResource);
        $this->assertEquals($newResource->amount, $updatedResource->amount);
        $this->assertEquals($newResource->resource_type_id, $updatedResource->resource_type_id);
        $this->assertDatabaseHas($resource->getTable(), [
            'id'               => $resource->id,
            'amount'           => $newResource->amount,
            'resource_type_id' => $newResource->resource_type_id,
        ]);
    }

    /**
     * @test
     * @covers ::delete
     */
    public function test_delete()
    {
        /** @var Resource $resource */
        $resource = Resource::factory()->create();

        $result = $this->repository->delete($resource->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing($resource->getTable(), [
            'id' => $resource->id
        ]);
    }
}
