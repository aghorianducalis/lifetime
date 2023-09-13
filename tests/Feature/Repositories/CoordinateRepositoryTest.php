<?php

namespace Tests\Feature\Repositories;

use App\Models\Coordinate;
use App\Models\Event;
use App\Models\User;
use App\Repositories\CoordinateRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Repositories\CoordinateRepository
 */
class CoordinateRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected CoordinateRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(CoordinateRepository::class);
    }

    /**
     * @test
     * @covers ::find
     */
    public function test_find()
    {
        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->create();

        $foundCoordinate = $this->repository->find($coordinate->id);

        $this->assertInstanceOf(Coordinate::class, $foundCoordinate);
        $this->assertEquals($coordinate->x, $foundCoordinate->x);
        $this->assertEquals($coordinate->y, $foundCoordinate->y);
        $this->assertEquals($coordinate->z, $foundCoordinate->z);
        $this->assertEquals($coordinate->t, $foundCoordinate->t);
    }

    /**
     * @test
     * @covers ::find
     */
    public function test_find_non_existing_coordinate()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->repository->find(self::NON_EXISTING_ID_INT);
    }

    /**
     * @test
     * @covers ::findByUser
     */
    public function test_find_by_user()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->withUsers([$user->id])->create();

        $foundCoordinates = $this->repository->findByUser($user->id);
        $this->assertCount(1, $foundCoordinates);
        $foundCoordinate = $foundCoordinates->first();
        $this->assertEquals($coordinate->id, $foundCoordinate->id);
        $this->assertEquals($user->id, $foundCoordinate->users()->first()->id);
    }

    /**
     * @test
     * @covers ::matching
     */
    public function test_get_all()
    {
        Coordinate::factory(5)->create();

        $coordinates = $this->repository->matching();

        $this->assertCount(5, $coordinates);
    }

    /**
     * @test
     * @covers ::create
     */
    public function test_create()
    {
        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->make();

        /** @var Coordinate $createdCoordinate */
        $createdCoordinate = $this->repository->create($coordinate->toArray());

        $this->assertInstanceOf(Coordinate::class, $createdCoordinate);
        $this->assertEquals($coordinate->x, $createdCoordinate->x);
        $this->assertEquals($coordinate->y, $createdCoordinate->y);
        $this->assertEquals($coordinate->z, $createdCoordinate->z);
        $this->assertEquals($coordinate->t, $createdCoordinate->t);
        $this->assertDatabaseHas($coordinate->getTable(), [
            'x' => $coordinate->x,
            'y' => $coordinate->y,
            'z' => $coordinate->z,
            't' => $coordinate->t,
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

        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->create();

        $this->repository->attachUsers($coordinate, [$user->id]);

        $this->assertCount(1, $user->coordinates);
        $this->assertCount(1, $coordinate->users);
        $this->assertEquals($coordinate->id, $user->coordinates()->first()->id);
        $this->assertEquals($user->id, $coordinate->users()->first()->id);
        $this->assertDatabaseHas($coordinate->users()->getTable(), [
            'user_id'       => $user->id,
            'coordinate_id' => $coordinate->id,
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

        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->withUsers([$user->id])->create();

        $detachedUsersCount = $this->repository->detachUsers($coordinate, [$user->id]);

        $this->assertEquals(1, $detachedUsersCount);
        $this->assertCount(0, $user->coordinates);
        $this->assertCount(0, $coordinate->users);
        $this->assertDatabaseMissing($coordinate->users()->getTable(), [
            'user_id'       => $user->id,
            'coordinate_id' => $coordinate->id,
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

        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->create();

        $this->repository->attachEvents($coordinate, [$event->id]);

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
     * @covers ::detachEvents
     */
    public function test_detach_events()
    {
        /** @var Event $event */
        $event = Event::factory()->create();

        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->withEvents([$event->id])->create();

        $detachedEventsCount = $this->repository->detachEvents($coordinate, [$event->id]);

        $this->assertEquals(1, $detachedEventsCount);
        $this->assertCount(0, $event->coordinates);
        $this->assertCount(0, $coordinate->events);
        $this->assertDatabaseMissing($coordinate->events()->getTable(), [
            'event_id'      => $event->id,
            'coordinate_id' => $coordinate->id,
        ]);
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_update_non_existing_coordinate()
    {
        $newData = Coordinate::factory()->make()->toArray();

        $this->expectException(ModelNotFoundException::class);
        $this->repository->update($newData, self::NON_EXISTING_ID_INT);
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_update()
    {
        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->create();
        /** @var Coordinate $newCoordinate */
        $newCoordinate = Coordinate::factory()->make();

        /** @var Coordinate $updatedCoordinate */
        $updatedCoordinate = $this->repository->update($newCoordinate->toArray(), $coordinate->id);

        $this->assertInstanceOf(Coordinate::class, $updatedCoordinate);
        $this->assertEquals($newCoordinate->x, $updatedCoordinate->x);
        $this->assertEquals($newCoordinate->y, $updatedCoordinate->y);
        $this->assertEquals($newCoordinate->z, $updatedCoordinate->z);
        $this->assertEquals($newCoordinate->t, $updatedCoordinate->t);
        $this->assertDatabaseHas($coordinate->getTable(), [
            'id' => $coordinate->id,
            'x'  => $newCoordinate->x,
            'y'  => $newCoordinate->y,
            'z'  => $newCoordinate->z,
            't'  => $newCoordinate->t,
        ]);
    }

    /**
     * @test
     * @covers ::delete
     */
    public function test_delete()
    {
        $coordinate = Coordinate::factory()->create();

        $result = $this->repository->delete($coordinate->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing($coordinate->getTable(), [
            'id' => $coordinate->id
        ]);
    }
}
