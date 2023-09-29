<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\Coordinate;
use App\Models\Event;
use App\Models\Resource;
use App\Models\ResourceType;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Repositories\UserRepository
 */
class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected UserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(UserRepository::class);
    }

    /**
     * @test
     * @covers ::find
     */
    public function test_find()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $foundUser = $this->repository->find($user->id);

        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->name, $foundUser->name);
        $this->assertEquals($user->email, $foundUser->email);
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
    public function test_get_all()
    {
        User::factory(5)->create();

        $users = $this->repository->matching();

        $this->assertCount(5, $users);
    }

    /**
     * @test
     * @covers ::create
     */
    public function test_create()
    {
        /** @var User $user */
        $user = User::factory()->make();
        $user->makeVisible($user->getAttributes());

        /** @var User $createdUser */
        $createdUser = $this->repository->create($user->getAttributes());

        $this->assertInstanceOf(User::class, $createdUser);
        $this->assertEquals($user->name, $createdUser->name);
        $this->assertEquals($user->email, $createdUser->email);
        $this->assertDatabaseHas($user->getTable(), [
            'name'  => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * @test
     * @covers ::attachEvents
     */
    public function test_attach_events()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Event $event */
        $event = Event::factory()->create();

        $this->repository->attachEvents($user, [$event->id]);

        $this->assertCount(1, $event->users);
        $this->assertCount(1, $user->events);
        $this->assertEquals($user->id, $event->users()->first()->id);
        $this->assertEquals($event->id, $user->events()->first()->id);
        $this->assertDatabaseHas($user->events()->getTable(), [
            'user_id'  => $user->id,
            'event_id' => $event->id,
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

        /** @var User $user */
        $user = User::factory()->withEvents([$event->id])->create();

        $detachedEventsCount = $this->repository->detachEvents($user, [$event->id]);

        $this->assertEquals(1, $detachedEventsCount);
        $this->assertCount(0, $event->users);
        $this->assertCount(0, $user->events);
        $this->assertDatabaseMissing($user->events()->getTable(), [
            'user_id'  => $user->id,
            'event_id' => $event->id,
        ]);
    }

    /**
     * @test
     * @covers ::attachCoordinates
     */
    public function test_attach_coordinates()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->create();

        $this->repository->attachCoordinates($user, [$coordinate->id]);

        $this->assertCount(1, $user->coordinates);
        $this->assertCount(1, $coordinate->users);
        $this->assertEquals($coordinate->id, $user->coordinates()->first()->id);
        $this->assertEquals($user->id, $coordinate->users()->first()->id);
        $this->assertDatabaseHas($user->coordinates()->getTable(), [
            'user_id'       => $user->id,
            'coordinate_id' => $coordinate->id,
        ]);
    }

    /**
     * @test
     * @covers ::detachCoordinates
     */
    public function test_detach_coordinates()
    {
        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->create();

        /** @var User $user */
        $user = User::factory()->withCoordinates([$coordinate->id])->create();

        $detachedCoordinatesCount = $this->repository->detachCoordinates($user, [$coordinate->id]);

        $this->assertEquals(1, $detachedCoordinatesCount);
        $this->assertCount(0, $coordinate->users);
        $this->assertCount(0, $user->coordinates);
        $this->assertDatabaseMissing($user->coordinates()->getTable(), [
            'user_id'       => $user->id,
            'coordinate_id' => $coordinate->id,
        ]);
    }

    /**
     * @test
     * @covers ::attachResources
     */
    public function test_attach_resources()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Resource $resource */
        $resource = Resource::factory()->create();

        $this->repository->attachResources($user, [$resource->id]);

        $this->assertCount(1, $resource->users);
        $this->assertCount(1, $user->resources);
        $this->assertEquals($user->id, $resource->users()->first()->id);
        $this->assertEquals($resource->id, $user->resources()->first()->id);
        $this->assertDatabaseHas($user->resources()->getTable(), [
            'user_id'     => $user->id,
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

        /** @var User $user */
        $user = User::factory()->withResources([$resource->id])->create();

        $detachedResourcesCount = $this->repository->detachResources($user, [$resource->id]);

        $this->assertEquals(1, $detachedResourcesCount);
        $this->assertCount(0, $resource->users);
        $this->assertCount(0, $user->resources);
        $this->assertDatabaseMissing($user->resources()->getTable(), [
            'user_id'     => $user->id,
            'resource_id' => $resource->id,
        ]);
    }

    /**
     * @test
     * @covers ::attachResourceTypes
     */
    public function test_attach_resource_types()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::factory()->create();

        $this->repository->attachResourceTypes($user, [$resourceType->id]);

        $this->assertCount(1, $user->resourceTypes);
        $this->assertCount(1, $resourceType->users);
        $this->assertEquals($resourceType->id, $user->resourceTypes()->first()->id);
        $this->assertEquals($user->id, $resourceType->users()->first()->id);
        $this->assertDatabaseHas($user->resourceTypes()->getTable(), [
            'user_id'          => $user->id,
            'resource_type_id' => $resourceType->id,
        ]);
    }

    /**
     * @test
     * @covers ::detachResourceTypes
     */
    public function test_detach_resource_types()
    {
        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::factory()->create();

        /** @var User $user */
        $user = User::factory()->withResourceTypes([$resourceType->id])->create();

        $detachedResourceTypesCount = $this->repository->detachResourceTypes($user, [$resourceType->id]);

        $this->assertEquals(1, $detachedResourceTypesCount);
        $this->assertCount(0, $user->resourceTypes);
        $this->assertCount(0, $resourceType->users);
        $this->assertDatabaseMissing($user->resourceTypes()->getTable(), [
            'user_id'          => $user->id,
            'resource_type_id' => $resourceType->id,
        ]);
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_update_non_existing_user()
    {
        $newData = User::factory()->make()->toArray();

        $this->expectException(ModelNotFoundException::class);
        $this->repository->update($newData, $this->getRandomUuid());
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_update()
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var User $newUser */
        $newUser = User::factory()->make();

        /** @var User $updatedUser */
        $updatedUser = $this->repository->update($newUser->toArray(), $user->id);

        $this->assertInstanceOf(User::class, $updatedUser);
        $this->assertEquals($newUser->name, $updatedUser->name);
        $this->assertEquals($newUser->email, $updatedUser->email);
        $this->assertDatabaseHas($user->getTable(), [
            'id'    => $user->id,
            'name'  => $newUser->name,
            'email' => $newUser->email,
        ]);
    }

    /**
     * @test
     * @covers ::delete
     */
    public function test_delete()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $result = $this->repository->delete($user->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing($user->getTable(), [
            'id' => $user->id
        ]);
    }
}
