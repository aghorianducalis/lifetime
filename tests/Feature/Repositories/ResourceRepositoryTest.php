<?php

namespace Tests\Feature\Repositories;

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
    public function testFind()
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
    public function testFindNonExistingResource()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->find($this->getRandomUuid());
    }

    /**
     * @test
     * @covers ::findByUser
     */
    public function testFindByUser()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Resource $resource */
        $resource = Resource::factory()->forUser($user)->create();

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
    public function testGetAll()
    {
        Resource::factory(5)->create();

        $resources = $this->repository->matching();

        $this->assertCount(5, $resources);
    }

    /**
     * @test
     * @covers ::create
     */
    public function testCreate()
    {
        $data = Resource::factory()->make()->toArray();

        $resource = $this->repository->create($data);

        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertEquals($data['amount'], $resource->amount);
        $this->assertEquals($data['resource_type_id'], $resource->resource_type_id);
        $this->assertDatabaseHas($resource->getTable(), [
            'amount'           => $data['amount'],
            'resource_type_id' => $data['resource_type_id']
        ]);
    }

    /**
     * @test
     * @covers ::update
     */
    public function testUpdateNonExistingResource()
    {
        $this->expectException(ModelNotFoundException::class);

        $newData = Resource::factory()->make()->toArray();
        $this->repository->update($newData, $this->getRandomUuid());
    }

    /**
     * @test
     * @covers ::update
     */
    public function testUpdate()
    {
        $resource = Resource::factory()->create();
        $newData = Resource::factory()->make()->toArray();

        $updatedResource = $this->repository->update($newData, $resource->id);

        $this->assertInstanceOf(Resource::class, $updatedResource);
        $this->assertEquals($newData['amount'], $updatedResource->amount);
        $this->assertEquals($newData['resource_type_id'], $updatedResource->resource_type_id);
        $this->assertDatabaseHas($resource->getTable(), [
            'id'               => $resource->id,
            'amount'           => $newData['amount'],
            'resource_type_id' => $newData['resource_type_id']
        ]);
    }

    /**
     * @test
     * @covers ::delete
     */
    public function testDelete()
    {
        $resource = Resource::factory()->create();

        $result = $this->repository->delete($resource->id);

        $this->assertTrue($result);

        $this->expectException(ModelNotFoundException::class);
        $foundResource = $this->repository->find($resource->id);
        $this->assertDatabaseMissing($resource->getTable(), [
            'id' => $resource->id
        ]);
    }
}
