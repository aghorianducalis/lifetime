<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\ResourceType;
use App\Models\User;
use App\Repositories\Interfaces\ResourceTypeRepositoryInterface;
use App\Services\ResourceTypeService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Services\ResourceTypeService
 */
class ResourceTypeServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @covers ::getAllResourceTypes
     */
    public function test_get_all_resource_types()
    {
        $resourceTypes = ResourceType::factory(5)->create();

        /** @var ResourceTypeRepositoryInterface $repositoryMock */
        $repositoryMock = $this->mock(ResourceTypeRepositoryInterface::class, function (MockInterface $mock) use ($resourceTypes) {
            $mock->shouldReceive('matching')->once()->andReturn($resourceTypes);
        });
        $service = $this->makeService($repositoryMock);

        $resourceTypes = $service->getAllResourceTypes();

        $this->assertCount(5, $resourceTypes);
    }

    /**
     * @test
     * @covers ::getResourceTypeById
     */
    public function test_get_resource_type_by_id()
    {
        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::factory()->create();

        /** @var ResourceTypeRepositoryInterface $repositoryMock */
        $repositoryMock = $this->mock(ResourceTypeRepositoryInterface::class, function (MockInterface $mock) use ($resourceType) {
            $mock->shouldReceive('find')->once()->with($resourceType->id)->andReturn($resourceType);
        });
        $service = $this->makeService($repositoryMock);

        $foundResourceType = $service->getResourceTypeById($resourceType->id);

        $this->assertInstanceOf(ResourceType::class, $foundResourceType);
        $this->assertEquals($resourceType->title, $foundResourceType->title);
        $this->assertEquals($resourceType->description, $foundResourceType->description);
    }

    /**
     * @test
     * @covers ::getResourceTypesByUser
     */
    public function test_get_resource_type_by_user()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $userId = $user->id;

        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::factory()->withUsers([$userId])->create();

        /** @var ResourceTypeRepositoryInterface $repositoryMock */
        $repositoryMock = $this->partialMock(ResourceTypeRepositoryInterface::class, function (MockInterface $mock) use ($userId, $resourceType) {
            $mock->shouldReceive('findByUser')->once()->with($userId)->andReturn(collect([$resourceType]));
        });
        $service = $this->makeService($repositoryMock);

        $foundResourceTypes = $service->getResourceTypesByUser($userId);

        $this->assertCount(1, $foundResourceTypes);
        $foundResourceType = $foundResourceTypes->first();
        $this->assertEquals($resourceType->id, $foundResourceType->id);
        $this->assertEquals($userId, $foundResourceType->users()->first()->id);
    }

    /**
     * @test
     * @covers ::doesResourceTypeBelongToUser
     */
    public function test_does_resource_type_belong_to_user()
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var User $anotherUser */
        $anotherUser = User::factory()->create();
        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::factory()->withUsers([$user->id])->create();

        $service = $this->makeService();

        $result = $service->doesResourceTypeBelongToUser($resourceType->id, $user->id);

        $this->assertTrue($result);

        $result = $service->doesResourceTypeBelongToUser($resourceType->id, $anotherUser->id);

        $this->assertFalse($result);
    }

    /**
     * @test
     * @covers ::createResourceType
     */
    public function test_create()
    {
        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::factory()->make();

        $createdResourceType = $this->makeService()->createResourceType($resourceType->toArray());

        $this->assertInstanceOf(ResourceType::class, $createdResourceType);
        $this->assertEquals($resourceType->title, $createdResourceType->title);
        $this->assertEquals($resourceType->description, $createdResourceType->description);
        $this->assertDatabaseHas($resourceType->getTable(), [
            'title'       => $resourceType->title,
            'description' => $resourceType->description,
        ]);
    }

    /**
     * @test
     * @covers ::updateResourceType
     */
    public function test_update()
    {
        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::factory()->create();
        /** @var ResourceType $newResourceType */
        $newResourceType = ResourceType::factory()->make();

        $updatedResourceType = $this->makeService()->updateResourceType($newResourceType->toArray(), $resourceType->id);

        $this->assertInstanceOf(ResourceType::class, $updatedResourceType);
        $this->assertEquals($newResourceType->title, $updatedResourceType->title);
        $this->assertEquals($newResourceType->description, $updatedResourceType->description);
        $this->assertDatabaseHas($resourceType->getTable(), [
            'id'          => $resourceType->id,
            'title'       => $updatedResourceType->title,
            'description' => $updatedResourceType->description,
        ]);
    }

    /**
     * @test
     * @covers ::deleteResourceType
     */
    public function test_delete()
    {
        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::factory()->create();

        $result = $this->makeService()->deleteResourceType($resourceType->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing($resourceType->getTable(), [
            'id' => $resourceType->id
        ]);
    }

    /**
     * @test
     * @covers ::getInstance
     */
    public function test_get_instance()
    {
        $service = ResourceTypeService::getInstance();

        $this->assertInstanceOf(ResourceTypeService::class, $service);

        $service2 = ResourceTypeService::getInstance();

        $this->assertInstanceOf(ResourceTypeService::class, $service2);
        $this->assertSame($service, $service2);
    }

    protected function makeService(ResourceTypeRepositoryInterface $repositoryMock = null): ResourceTypeService
    {
        if ($repositoryMock) {
            $this->app->bind(ResourceTypeService::class, function (Application $app) use ($repositoryMock) {
                return new ResourceTypeService($repositoryMock);
            });
        }

        return app()->make(ResourceTypeService::class);
    }
}
