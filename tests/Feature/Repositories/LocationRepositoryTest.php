<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\Coordinate;
use App\Models\Location;
use App\Models\User;
use App\Repositories\LocationRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Repositories\LocationRepository
 */
class LocationRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected LocationRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(LocationRepository::class);
    }

    /**
     * @test
     * @covers ::find
     */
    public function test_find()
    {
        /** @var Location $location */
        $location = Location::factory()->create();

        $foundLocation = $this->repository->find($location->id);

        $this->assertInstanceOf(Location::class, $foundLocation);
        $this->assertEquals($location->title, $foundLocation->title);
        $this->assertEquals($location->description, $foundLocation->description);
        $this->assertEquals($location->coordinate_id, $foundLocation->coordinate_id);
    }

    /**
     * @test
     * @covers ::find
     */
    public function test_find_non_existing_location()
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
        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->withUsers([$user->id])->create();
        /** @var Location $location */
        $location = Location::factory()->create(['coordinate_id' => $coordinate->id]);

        $foundLocations = $this->repository->findByUser($user->id);
        $this->assertCount(1, $foundLocations);
        /** @var Location $foundLocation */
        $foundLocation = $foundLocations->first();
        $this->assertEquals($location->id, $foundLocation->id);
        $this->assertEquals($user->id, $foundLocation->coordinate->users->first()->id);
    }

    /**
     * @test
     * @covers ::findByCoordinateIds
     */
    public function test_find_by_coordinate_ids()
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->withUsers([$user->id])->create();
        /** @var Location $location */
        $location = Location::factory()->create(['coordinate_id' => $coordinate->id]);

        $foundLocations = $this->repository->findByCoordinateIds([$coordinate->id]);
        $this->assertCount(1, $foundLocations);
        /** @var Location $foundLocation */
        $foundLocation = $foundLocations->first();
        $this->assertEquals($location->id, $foundLocation->id);
        $this->assertEquals($user->id, $foundLocation->coordinate->users->first()->id);
    }

    /**
     * @test
     * @covers ::matching
     */
    public function test_get_all()
    {
        Location::factory(5)->create();

        $locations = $this->repository->matching();

        $this->assertCount(5, $locations);
    }

    /**
     * @test
     * @covers ::create
     */
    public function test_create()
    {
        /** @var Location $location */
        $location = Location::factory()->make();

        /** @var Location $createdLocation */
        $createdLocation = $this->repository->create($location->toArray());

        $this->assertInstanceOf(Location::class, $location);
        $this->assertEquals($location->title, $createdLocation->title);
        $this->assertEquals($location->description, $createdLocation->description);
        $this->assertEquals($location->coordinate_id, $createdLocation->coordinate_id);
        $this->assertDatabaseHas($location->getTable(), [
            'title'         => $location->title,
            'description'   => $location->description,
            'coordinate_id' => $location->coordinate_id,
        ]);
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_update_non_existing_location()
    {
        $newData = Location::factory()->make()->toArray();

        $this->expectException(ModelNotFoundException::class);
        $this->repository->update($newData, $this->getRandomUuid());
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_update()
    {
        /** @var Location $location */
        $location = Location::factory()->create();
        /** @var Location $newLocation */
        $newLocation = Location::factory()->make();

        /** @var Location $updatedLocation */
        $updatedLocation = $this->repository->update($newLocation->toArray(), $location->id);

        $this->assertInstanceOf(Location::class, $updatedLocation);
        $this->assertEquals($newLocation->title, $updatedLocation->title);
        $this->assertEquals($newLocation->description, $updatedLocation->description);
        $this->assertEquals($newLocation->coordinate_id, $updatedLocation->coordinate_id);
        $this->assertDatabaseHas($location->getTable(), [
            'id'            => $location->id,
            'title'         => $newLocation->title,
            'description'   => $newLocation->description,
            'coordinate_id' => $newLocation->coordinate_id,
        ]);
    }

    /**
     * @test
     * @covers ::delete
     */
    public function test_delete()
    {
        /** @var Location $location */
        $location = Location::factory()->create();

        $result = $this->repository->delete($location->id);

        $this->assertTrue($result);

        $this->assertDatabaseMissing($location->getTable(), [
            'id' => $location->id
        ]);
    }
}
