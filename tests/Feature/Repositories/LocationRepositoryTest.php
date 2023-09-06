<?php

namespace Tests\Feature\Repositories;

use App\Models\Location;
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
    public function testFind()
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
    public function testFindNonExistingLocation()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->find($this->getRandomUuid());
    }

    /**
     * @test
     * @covers ::matching
     */
    public function testGetAll()
    {
        Location::factory(5)->create();

        $locations = $this->repository->matching();

        $this->assertCount(5, $locations);
    }

    /**
     * @test
     * @covers ::create
     */
    public function testCreate()
    {
        $data = Location::factory()->make()->toArray();

        $location = $this->repository->create($data);

        $this->assertInstanceOf(Location::class, $location);
        $this->assertEquals($data['title'], $location->title);
        $this->assertEquals($data['description'], $location->description);
        $this->assertEquals($data['coordinate_id'], $location->coordinate_id);
        $this->assertDatabaseHas($location->getTable(), [
            'title'         => $data['title'],
            'description'   => $data['description'],
            'coordinate_id' => $data['coordinate_id'],
        ]);
    }

    /**
     * @test
     * @covers ::update
     */
    public function testUpdateNonExistingLocation()
    {
        $this->expectException(ModelNotFoundException::class);

        $newData = Location::factory()->make()->toArray();
        $this->repository->update($newData, $this->getRandomUuid());
    }

    /**
     * @test
     * @covers ::update
     */
    public function testUpdate()
    {
        $location = Location::factory()->create();
        $newData = Location::factory()->make()->toArray();

        $updatedLocation = $this->repository->update($newData, $location->id);

        $this->assertInstanceOf(Location::class, $updatedLocation);
        $this->assertEquals($newData['title'], $updatedLocation->title);
        $this->assertEquals($newData['description'], $updatedLocation->description);
        $this->assertEquals($newData['coordinate_id'], $updatedLocation->coordinate_id);
        $this->assertDatabaseHas($location->getTable(), [
            'id'            => $location->id,
            'title'         => $newData['title'],
            'description'   => $newData['description'],
            'coordinate_id' => $newData['coordinate_id'],
        ]);
    }

    /**
     * @test
     * @covers ::delete
     */
    public function testDelete()
    {
        $location = Location::factory()->create();

        $result = $this->repository->delete($location->id);

        $this->assertTrue($result);

        $this->expectException(ModelNotFoundException::class);
        $foundLocation = $this->repository->find($location->id);
        $this->assertDatabaseMissing($location->getTable(), [
            'id' => $location->id
        ]);
    }
}
