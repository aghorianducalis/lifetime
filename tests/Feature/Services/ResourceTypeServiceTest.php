<?php

namespace Tests\Feature\Services;

use App\Models\ResourceType;
use App\Models\User;
use App\Repositories\Interfaces\ResourceTypeRepositoryInterface;
use App\Services\ResourceTypeService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Services\ResourceTypeService
 */
class ResourceTypeServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ResourceTypeService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ResourceTypeService::class);
    }

    /**
     * @test
     * @covers ::getResourceTypeById
     */
    public function testGetResourceTypeById()
    {
        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::factory()->create();

        $foundResourceType = $this->service->getResourceTypeById($resourceType->id);

        $this->assertInstanceOf(ResourceType::class, $foundResourceType);
        $this->assertEquals($resourceType->title, $foundResourceType->title);
        $this->assertEquals($resourceType->description, $foundResourceType->description);
    }

    /**
     * @test
     * @covers ::getResourceTypesByUser
     */
    public function testGetResourceTypeByUser()
    {
        /** @var User $user */
        $user = User::factory()->create();

        // expect to call the findByUser method on repository object
        $repository = $this->mock(ResourceTypeRepositoryInterface::class);
        // todo check why this returns an error
//        $repository->shouldReceive('findByUser')->once()->with($user->id)->andReturn([]);

        $result = $this->service->getResourceTypesByUser($user->id);

        $this->assertEquals([], $result->toArray());

        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::factory()->forUser($user)->create();

        $foundResourceTypes = $this->service->getResourceTypesByUser($user->id);
        $this->assertCount(1, $foundResourceTypes);
        $foundResourceType = $foundResourceTypes->first();
        $this->assertEquals($resourceType->id, $foundResourceType->id);
        $this->assertEquals($user->id, $foundResourceType->users()->first()->id);
    }

    /**
     * @test
     * @covers ::getAllResourceTypes
     */
    public function testGetAllResourceTypes()
    {
        ResourceType::factory(5)->create();

        $resourceTypes = $this->service->getAllResourceTypes();

        $this->assertCount(5, $resourceTypes);
    }

    /**
     * @test
     * @covers ::createResourceType
     */
    public function testCreate()
    {
        $data = ResourceType::factory()->make()->toArray();

        $resourceType = $this->service->createResourceType($data);

        $this->assertInstanceOf(ResourceType::class, $resourceType);
        $this->assertEquals($data['title'], $resourceType->title);
        $this->assertEquals($data['description'], $resourceType->description);
        $this->assertDatabaseHas($resourceType->getTable(), [
            'title'       => $data['title'],
            'description' => $data['description'],
        ]);
    }

    /**
     * @test
     * @covers ::updateResourceType
     */
    public function testUpdate()
    {
        $resourceType = ResourceType::factory()->create();
        $newData = ResourceType::factory()->make()->toArray();

        $updatedResourceType = $this->service->updateResourceType($newData, $resourceType->id);

        $this->assertInstanceOf(ResourceType::class, $updatedResourceType);
        $this->assertEquals($newData['title'], $updatedResourceType->title);
        $this->assertEquals($newData['description'], $updatedResourceType->description);
        $this->assertDatabaseHas($resourceType->getTable(), [
            'id'          => $resourceType->id,
            'title'       => $newData['title'],
            'description' => $newData['description'],
        ]);
    }

    /**
     * @test
     * @covers ::deleteResourceType
     */
    public function testDelete()
    {
        $resourceType = ResourceType::factory()->create();

        $result = $this->service->deleteResourceType($resourceType->id);

        $this->assertTrue($result);

        $this->expectException(ModelNotFoundException::class);
        $this->service->getResourceTypeById($resourceType->id);
        $this->assertDatabaseMissing($resourceType->getTable(), [
            'id' => $resourceType->id
        ]);
    }
}
