<?php

namespace Tests\Feature\Services;

use App\Models\Coordinate;
use App\Models\User;
use App\Repositories\Interfaces\CoordinateRepositoryInterface;
use App\Services\CoordinateService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
    public function testGetCoordinateById()
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
    public function testGetCoordinateByUser()
    {
        /** @var User $user */
        $user = User::factory()->create();

        // expect to call the findByUser method on repository object
        $repository = $this->mock(CoordinateRepositoryInterface::class);
        // todo check why this returns an error
//        $repository->shouldReceive('findByUser')->once()->with($user->id)->andReturn([]);

        $result = $this->service->getCoordinatesByUser($user->id);

        $this->assertEquals([], $result->toArray());

        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->forUser($user)->create();

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
    public function testGetAllCoordinates()
    {
        Coordinate::factory(5)->create();

        $coordinates = $this->service->getAllCoordinates();

        $this->assertCount(5, $coordinates);
    }

    /**
     * @test
     * @covers ::createCoordinate
     */
    public function testCreate()
    {
        $data = Coordinate::factory()->make()->toArray();

        $coordinate = $this->service->createCoordinate($data);

        $this->assertInstanceOf(Coordinate::class, $coordinate);
        $this->assertEquals($data['x'], $coordinate->x);
        $this->assertEquals($data['y'], $coordinate->y);
        $this->assertEquals($data['z'], $coordinate->z);
        $this->assertEquals($data['t'], $coordinate->t->toISOString());
        $this->assertDatabaseHas($coordinate->getTable(), [
            'x' => $data['x'],
            'y' => $data['y'],
            'z' => $data['z'],
            't' => $data['t'],
        ]);
    }

    /**
     * @test
     * @covers ::updateCoordinate
     */
    public function testUpdate()
    {
        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->create();
        $newData = Coordinate::factory()->make()->toArray();

        /** @var Coordinate $updatedCoordinate */
        $updatedCoordinate = $this->service->updateCoordinate($newData, $coordinate->id);

        $this->assertEquals($newData['x'], $updatedCoordinate->x);
        $this->assertEquals($newData['y'], $updatedCoordinate->y);
        $this->assertEquals($newData['z'], $updatedCoordinate->z);
        $this->assertEquals($newData['t'], $updatedCoordinate->t->toISOString());
        $this->assertDatabaseHas($coordinate->getTable(), [
            'id' => $coordinate->id,
            'x'  => $newData['x'],
            'y'  => $newData['y'],
            'z'  => $newData['z'],
            't'  => $newData['t'],
        ]);
    }

    /**
     * @test
     * @covers ::deleteCoordinate
     */
    public function testDelete()
    {
        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->create();

        $result = $this->service->deleteCoordinate($coordinate->id);

        $this->assertTrue($result);

        $this->expectException(ModelNotFoundException::class);
        $this->service->getCoordinateById($coordinate->id);
        $this->assertDatabaseMissing($coordinate->getTable(), [
            'id' => $coordinate->id
        ]);
    }
}
