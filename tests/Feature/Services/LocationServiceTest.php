<?php

namespace Tests\Feature\Services;

use App\Models\Location;
use App\Services\LocationService;
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
    public function test_get_location_by_id()
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
    public function test_get_all_locations()
    {
        Location::factory(5)->create();

        $locations = $this->service->getAllLocations();

        $this->assertCount(5, $locations);
    }

    /**
     * @test
     * @covers ::createLocation
     */
    public function test_create()
    {
        /** @var Location $location */
        $location = Location::factory()->make();

        $createdLocation = $this->service->createLocation($location->toArray());

        $this->assertInstanceOf(Location::class, $createdLocation);
        $this->assertEquals($location->title, $createdLocation->title);
        $this->assertEquals($location->description, $createdLocation->description);
        $this->assertEquals($location->coordinate_id, $createdLocation->coordinate_id);
        $this->assertDatabaseHas($createdLocation->getTable(), [
            'title'         => $location->title,
            'description'   => $location->description,
            'coordinate_id' => $location->coordinate_id,
        ]);
    }

    /**
     * @test
     * @covers ::updateLocation
     */
    public function test_update()
    {
        /** @var Location $location */
        $location = Location::factory()->create();
        /** @var Location $newLocation */
        $newLocation = Location::factory()->make();

        $updatedLocation = $this->service->updateLocation($newLocation->toArray(), $location->id);

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
     * @covers ::deleteLocation
     */
    public function test_delete()
    {
        /** @var Location $location */
        $location = Location::factory()->create();

        $result = $this->service->deleteLocation($location->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing($location->getTable(), [
            'id' => $location->id
        ]);
    }
}
