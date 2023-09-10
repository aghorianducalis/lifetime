<?php

namespace Tests\Feature\Services;

use App\Models\Location;
use App\Services\LocationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Services\LocationService
 */
class LocationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected LocationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(LocationService::class);
    }

    /**
     * @test
     * @covers ::getLocationById
     */
    public function testGetLocationById()
    {
        /** @var Location $location */
        $location = Location::factory()->create();

        $foundLocation = $this->service->getLocationById($location->id);

        $this->assertInstanceOf(Location::class, $foundLocation);
        $this->assertEquals($location->title, $foundLocation->title);
        $this->assertEquals($location->description, $foundLocation->description);
        $this->assertEquals($location->coordinate_id, $foundLocation->coordinate_id);
    }

    /**
     * @test
     * @covers ::getAllLocations
     */
    public function testGetAllLocations()
    {
        Location::factory(5)->create();

        $locations = $this->service->getAllLocations();

        $this->assertCount(5, $locations);
    }

    /**
     * @test
     * @covers ::createLocation
     */
    public function testCreate()
    {
        $data = Location::factory()->make()->toArray();

        $location = $this->service->createLocation($data);

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
     * @covers ::updateLocation
     */
    public function testUpdate()
    {
        /** @var Location $location */
        $location = Location::factory()->create();
        $newData = Location::factory()->make()->toArray();

        $updatedLocation = $this->service->updateLocation($newData, $location->id);

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
     * @covers ::deleteLocation
     */
    public function testDelete()
    {
        /** @var Location $location */
        $location = Location::factory()->create();

        $result = $this->service->deleteLocation($location->id);

        $this->assertTrue($result);

        $this->expectException(ModelNotFoundException::class);
        $this->service->getLocationById($location->id);
        $this->assertDatabaseMissing($location->getTable(), [
            'id' => $location->id
        ]);
    }
}
