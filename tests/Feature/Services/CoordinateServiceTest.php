<?php

namespace Tests\Feature\Services;

use App\Models\Coordinate;
use App\Models\User;
use App\Repositories\Interfaces\CoordinateRepositoryInterface;
use App\Services\CoordinateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Services\CoordinateService
 */
class CoordinateServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CoordinateService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CoordinateService::class);
    }

    /**
     * @test
     * @covers ::getCoordinateById
     */
    public function test_get_coordinate_by_id()
    {
        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->create();

        $foundCoordinate = $this->service->getCoordinateById($coordinate->id);

        $this->assertInstanceOf(Coordinate::class, $foundCoordinate);
        $this->assertEquals($coordinate->x, $foundCoordinate->x);
        $this->assertEquals($coordinate->y, $foundCoordinate->y);
        $this->assertEquals($coordinate->z, $foundCoordinate->z);
        $this->assertEquals($coordinate->t, $foundCoordinate->t);
    }

    /**
     * @test
     * @covers ::getCoordinatesByUser
     */
    public function test_get_coordinate_by_user()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $result = $this->service->getCoordinatesByUser($user->id);

        $this->assertEquals([], $result->toArray());

        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->withUsers([$user->id])->create();

        $foundCoordinates = $this->service->getCoordinatesByUser($user->id);
        $this->assertCount(1, $foundCoordinates);
        $foundCoordinate = $foundCoordinates->first();
        $this->assertEquals($coordinate->id, $foundCoordinate->id);
        $this->assertEquals($user->id, $foundCoordinate->users()->first()->id);
    }

    /**
     * @test
     * @covers ::getAllCoordinates
     */
    public function test_get_all_coordinates()
    {
        Coordinate::factory(5)->create();

        $coordinates = $this->service->getAllCoordinates();

        $this->assertCount(5, $coordinates);
    }

    /**
     * @test
     * @covers ::createCoordinate
     */
    public function test_create()
    {
        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->make();

        $createdCoordinate = $this->service->createCoordinate($coordinate->toArray());

        $this->assertInstanceOf(Coordinate::class, $createdCoordinate);
        $this->assertEquals($coordinate->x, $createdCoordinate->x);
        $this->assertEquals($coordinate->y, $createdCoordinate->y);
        $this->assertEquals($coordinate->z, $createdCoordinate->z);
        $this->assertEquals($coordinate->t, $createdCoordinate->t);
        $this->assertDatabaseHas($createdCoordinate->getTable(), [
            'x' => $coordinate->x,
            'y' => $coordinate->y,
            'z' => $coordinate->z,
            't' => $coordinate->t,
        ]);
    }

    /**
     * @test
     * @covers ::updateCoordinate
     */
    public function test_update()
    {
        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->create();
        /** @var Coordinate $newCoordinate */
        $newCoordinate = Coordinate::factory()->create();

        /** @var Coordinate $updatedCoordinate */
        $updatedCoordinate = $this->service->updateCoordinate($newCoordinate->toArray(), $coordinate->id);

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
     * @covers ::deleteCoordinate
     */
    public function test_delete()
    {
        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->create();

        $result = $this->service->deleteCoordinate($coordinate->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing($coordinate->getTable(), [
            'id' => $coordinate->id
        ]);
    }
}
