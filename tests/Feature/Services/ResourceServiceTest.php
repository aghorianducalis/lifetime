<?php

namespace Tests\Feature\Services;

use App\Models\Resource;
use App\Models\User;
use App\Repositories\Interfaces\ResourceRepositoryInterface;
use App\Services\ResourceService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Services\ResourceService
 */
class ResourceServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ResourceService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ResourceService::class);
    }

    /**
     * @test
     * @covers ::getResourceById
     */
    public function testGetResourceById()
    {
        /** @var Resource $resource */
        $resource = Resource::factory()->create();

        $foundResource = $this->service->getResourceById($resource->id);

        $this->assertInstanceOf(Resource::class, $foundResource);
        $this->assertEquals($resource->amount, $foundResource->amount);
        $this->assertEquals($resource->resource_type_id, $foundResource->resource_type_id);
    }

    /**
     * @test
     * @covers ::getResourcesByUser
     */
    public function testGetResourceByUser()
    {
        /** @var User $user */
        $user = User::factory()->create();

        // expect to call the findByUser method on repository object
        $repository = $this->mock(ResourceRepositoryInterface::class);
        // todo check why this returns an error
//        $repository->shouldReceive('findByUser')->once()->with($user->id)->andReturn([]);

        $result = $this->service->getResourcesByUser($user->id);

        $this->assertEquals([], $result->toArray());

        /** @var Resource $resource */
        $resource = Resource::factory()->forUser($user)->create();

        $foundResources = $this->service->getResourcesByUser($user->id);
        $this->assertCount(1, $foundResources);
        $foundResource = $foundResources->first();
        $this->assertEquals($resource->id, $foundResource->id);
        $this->assertEquals($user->id, $foundResource->users()->first()->id);
    }

    /**
     * @test
     * @covers ::getAllResources
     */
    public function testGetAllResources()
    {
        Resource::factory(5)->create();

        $resources = $this->service->getAllResources();

        $this->assertCount(5, $resources);
    }

    /**
     * @test
     * @covers ::createResource
     */
    public function testCreate()
    {
        $data = Resource::factory()->make()->toArray();

        $resource = $this->service->createResource($data);

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
     * @covers ::updateResource
     */
    public function testUpdate()
    {
        /** @var Resource $resource */
        $resource = Resource::factory()->create();
        $newData = Resource::factory()->make()->toArray();

        $updatedResource = $this->service->updateResource($newData, $resource->id);

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
     * @covers ::deleteResource
     */
    public function testDelete()
    {
        /** @var Resource $resource */
        $resource = Resource::factory()->create();

        $result = $this->service->deleteResource($resource->id);

        $this->assertTrue($result);

        $this->expectException(ModelNotFoundException::class);
        $this->service->getResourceById($resource->id);
        $this->assertDatabaseMissing($resource->getTable(), [
            'id' => $resource->id
        ]);
    }
}
