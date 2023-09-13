<?php

namespace Tests\Feature\Services;

use App\Models\Resource;
use App\Models\User;
use App\Repositories\Interfaces\ResourceRepositoryInterface;
use App\Services\ResourceService;
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
    public function test_get_resource_by_id()
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
    public function test_get_resource_by_user()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $result = $this->service->getResourcesByUser($user->id);

        $this->assertEquals([], $result->toArray());

        /** @var Resource $resource */
        $resource = Resource::factory()->withUsers([$user->id])->create();

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
    public function test_get_all_resources()
    {
        Resource::factory(5)->create();

        $resources = $this->service->getAllResources();

        $this->assertCount(5, $resources);
    }

    /**
     * @test
     * @covers ::createResource
     */
    public function test_create()
    {
        /** @var Resource $resource */
        $resource = Resource::factory()->make();

        $createdResource = $this->service->createResource($resource->toArray());

        $this->assertInstanceOf(Resource::class, $createdResource);
        $this->assertEquals($resource->amount, $createdResource->amount);
        $this->assertEquals($resource->resource_type_id, $createdResource->resource_type_id);
        $this->assertDatabaseHas($createdResource->getTable(), [
            'amount'           => $resource->amount,
            'resource_type_id' => $resource->resource_type_id,
        ]);
    }

    /**
     * @test
     * @covers ::updateResource
     */
    public function test_update()
    {
        /** @var Resource $resource */
        $resource = Resource::factory()->create();
        /** @var Resource $newResource */
        $newResource = Resource::factory()->make();

        $updatedResource = $this->service->updateResource($newResource->toArray(), $resource->id);

        $this->assertInstanceOf(Resource::class, $updatedResource);
        $this->assertEquals($newResource->amount, $updatedResource->amount);
        $this->assertEquals($newResource->resource_type_id, $updatedResource->resource_type_id);
        $this->assertDatabaseHas($resource->getTable(), [
            'id'               => $resource->id,
            'amount'           => $newResource->amount,
            'resource_type_id' => $newResource->resource_type_id,
        ]);
    }

    /**
     * @test
     * @covers ::deleteResource
     */
    public function test_delete()
    {
        /** @var Resource $resource */
        $resource = Resource::factory()->create();

        $result = $this->service->deleteResource($resource->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing($resource->getTable(), [
            'id' => $resource->id
        ]);
    }
}
