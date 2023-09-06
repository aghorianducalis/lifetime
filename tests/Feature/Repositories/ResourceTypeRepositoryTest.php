<?php

namespace Tests\Feature\Repositories;

use App\Models\ResourceType;
use App\Models\User;
use App\Repositories\Filters\Criteria;
use App\Repositories\Filters\TitleFilter;
use App\Repositories\ResourceTypeRepository;
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
    public function testFind()
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

        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::factory()->forUser($user)->create();

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
    public function testGetAll()
    {
        ResourceType::factory(5)->create();

        $resourceTypes = $this->repository->matching();

        $this->assertCount(5, $resourceTypes);
    }

    /**
     * @test
     * @covers ::matching
     */
    public function testMatching()
    {
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
    public function testCreate()
    {
        $data = ResourceType::factory()->make()->toArray();

        $resourceType = $this->repository->create($data);

        $this->assertInstanceOf(ResourceType::class, $resourceType);
        $this->assertEquals($data['title'], $resourceType->title);
        $this->assertEquals($data['description'], $resourceType->description);
        $this->assertDatabaseHas($resourceType->getTable(), [
            'title'       => $data['title'],
            'description' => $data['description']
        ]);
    }

    /**
     * @test
     * @covers ::update
     */
    public function testUpdateNonExistingResource()
    {
        $this->expectException(ModelNotFoundException::class);

        $newData = ResourceType::factory()->make()->toArray();
        $this->repository->update($newData, $this->getRandomUuid());
    }

    /**
     * @test
     * @covers ::update
     */
    public function testUpdate()
    {
        $resourceType = ResourceType::factory()->create();
        $newData = ResourceType::factory()->make()->toArray();

        $updatedResourceType = $this->repository->update($newData, $resourceType->id);

        $this->assertInstanceOf(ResourceType::class, $updatedResourceType);
        $this->assertEquals($newData['title'], $updatedResourceType->title);
        $this->assertEquals($newData['description'], $updatedResourceType->description);
        $this->assertDatabaseHas($resourceType->getTable(), [
            'id'          => $resourceType->id,
            'title'       => $newData['title'],
            'description' => $newData['description']
        ]);
    }

    /**
     * @test
     * @covers ::delete
     */
    public function testDelete()
    {
        $resourceType = ResourceType::factory()->create();

        $result = $this->repository->delete($resourceType->id);

        $this->assertTrue($result);

        $this->expectException(ModelNotFoundException::class);
        $foundResourceType = $this->repository->find($resourceType->id);
        $this->assertDatabaseMissing($resourceType->getTable(), [
            'id' => $resourceType->id
        ]);
    }
}
