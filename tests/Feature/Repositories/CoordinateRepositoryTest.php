<?php

namespace Tests\Feature\Repositories;

use App\Models\Coordinate;
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
    public function testFind()
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
    public function testFindNonExistingCoordinate()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->find(self::NON_EXISTING_ID_INT);
    }

    /**
     * @test
     * @covers ::findByUser
     */
    public function testFindByUser()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->forUser($user)->create();

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
    public function testGetAll()
    {
        Coordinate::factory(5)->create();

        $coordinates = $this->repository->matching();

        $this->assertCount(5, $coordinates);
    }

    /**
     * @test
     * @covers ::create
     */
    public function testCreate()
    {
        $data = Coordinate::factory()->make()->toArray();

        $coordinate = $this->repository->create($data);

        $this->assertInstanceOf(Coordinate::class, $coordinate);
        $this->assertEquals($data['x'], $coordinate->x);
        $this->assertEquals($data['y'], $coordinate->y);
        $this->assertEquals($data['z'], $coordinate->z);
        $this->assertEquals($data['t'], $coordinate->t);
        $this->assertDatabaseHas($coordinate->getTable(), [
            'x' => $data['x'],
            'y' => $data['y'],
            'z' => $data['z'],
            't' => $data['t'],
        ]);
    }

    /**
     * @test
     * @covers ::update
     */
    public function testUpdateNonExistingCoordinate()
    {
        $this->expectException(ModelNotFoundException::class);

        $newData = Coordinate::factory()->make()->toArray();
        $this->repository->update($newData, self::NON_EXISTING_ID_INT);
    }

    /**
     * @test
     * @covers ::update
     */
    public function testUpdate()
    {
        $coordinate = Coordinate::factory()->create();
        $newData = Coordinate::factory()->make()->toArray();

        $updatedCoordinate = $this->repository->update($newData, $coordinate->id);

        $this->assertInstanceOf(Coordinate::class, $updatedCoordinate);

        $this->assertEquals($newData['x'], $updatedCoordinate->x);
        $this->assertEquals($newData['y'], $updatedCoordinate->y);
        $this->assertEquals($newData['z'], $updatedCoordinate->z);
        $this->assertEquals($newData['t'], $updatedCoordinate->t);
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
     * @covers ::delete
     */
    public function testDelete()
    {
        $coordinate = Coordinate::factory()->create();

        $result = $this->repository->delete($coordinate->id);

        $this->assertTrue($result);

        $this->expectException(ModelNotFoundException::class);
        $foundCoordinate = $this->repository->find($coordinate->id);
        $this->assertDatabaseMissing($coordinate->getTable(), [
            'id' => $coordinate->id
        ]);
    }
}
