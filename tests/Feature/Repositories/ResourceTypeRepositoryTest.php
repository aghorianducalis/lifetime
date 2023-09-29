<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\ResourceType;
use App\Models\User;
use App\Repositories\Filters\Criteria;
use App\Repositories\Filters\TitleFilter;
use App\Repositories\ResourceTypeRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Repositories\ResourceTypeRepository
 */
class ResourceTypeRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected ResourceTypeRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(ResourceTypeRepository::class);
    }

    /**
     * @test
     * @covers ::find
     */
    public function test_find()
    {
        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::factory()->create();

        $foundResourceType = $this->repository->find($resourceType->id);

        $this->assertInstanceOf(ResourceType::class, $foundResourceType);
        $this->assertEquals($resourceType->title, $foundResourceType->title);
        $this->assertEquals($resourceType->description, $foundResourceType->description);
    }

    /**
     * @test
     * @covers ::find
     */
    public function test_find_non_existing_resource()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->repository->find($this->getRandomUuid());
    }

    /**
     * @test
     * @covers ::findByUser
     */
    public function test_find_by_user()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::factory()->withUsers([$user->id])->create();

        $foundResourceTypes = $this->repository->findByUser($user->id);
        $this->assertCount(1, $foundResourceTypes);
        $foundResourceType = $foundResourceTypes->first();
        $this->assertEquals($resourceType->id, $foundResourceType->id);
        $this->assertEquals($user->id, $foundResourceType->users()->first()->id);
    }

    /**
     * @test
     * @covers ::matching
     */
    public function test_get_all()
    {
        ResourceType::factory(5)->create();

        $resourceTypes = $this->repository->matching();

        $this->assertCount(5, $resourceTypes);
    }

    /**
     * @test
     * @covers ::matching
     */
    public function test_matching()
    {
        /** @var Collection<int,ResourceType> $resourceTypesCreated */
        $resourceTypesCreated = ResourceType::factory(5)->create();

        $criteria = new Criteria;
        $criteria->push(new TitleFilter($resourceTypesCreated->random()->title));

        $resourceTypesObtained = $this->repository->matching($criteria);

        $this->assertCount(1, $resourceTypesObtained);
    }

    /**
     * @test
     * @covers ::create
     */
    public function test_create()
    {
        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::factory()->make();

        /** @var ResourceType $createdResourceType */
        $createdResourceType = $this->repository->create($resourceType->toArray());

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
     * @covers ::attachUsers
     */
    public function test_attach_users()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::factory()->create();

        $this->repository->attachUsers($resourceType, [$user->id]);

        $this->assertCount(1, $user->resourceTypes);
        $this->assertCount(1, $resourceType->users);
        $this->assertEquals($resourceType->id, $user->resourceTypes()->first()->id);
        $this->assertEquals($user->id, $resourceType->users()->first()->id);
        $this->assertDatabaseHas($resourceType->users()->getTable(), [
            'resource_type_id' => $resourceType->id,
            'user_id'          => $user->id,
        ]);
    }

    /**
     * @test
     * @covers ::detachUsers
     */
    public function test_detach_users()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::factory()->withUsers([$user->id])->create();

        $detachedUsersCount = $this->repository->detachUsers($resourceType, [$user->id]);

        $this->assertEquals(1, $detachedUsersCount);
        $this->assertCount(0, $user->resourceTypes);
        $this->assertCount(0, $resourceType->users);
        $this->assertDatabaseMissing($resourceType->users()->getTable(), [
            'resource_type_id' => $resourceType->id,
            'user_id'          => $user->id,
        ]);
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_update_non_existing_resource()
    {
        $newData = ResourceType::factory()->make()->toArray();

        $this->expectException(ModelNotFoundException::class);
        $this->repository->update($newData, $this->getRandomUuid());
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_update()
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::factory()->withUsers([$user->id])->create();
        /** @var ResourceType $newResourceType */
        $newResourceType = ResourceType::factory()->make();

        $updatedResourceType = $this->repository->update($newResourceType->toArray(), $resourceType->id);

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
     * @covers ::delete
     */
    public function test_delete()
    {
        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::factory()->create();

        $result = $this->repository->delete($resourceType->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing($resourceType->getTable(), [
            'id' => $resourceType->id
        ]);
    }
}
