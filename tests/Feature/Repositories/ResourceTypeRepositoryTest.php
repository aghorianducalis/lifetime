<?php

namespace Tests\Feature\Repositories;

use App\Models\ResourceType;
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

    protected ResourceTypeRepository $resourceTypeRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resourceTypeRepository = new ResourceTypeRepository();
    }

    /**
     * @test
     * @covers ::find
     */
    public function testFind()
    {
        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::factory()->create();

        $foundResourceType = $this->resourceTypeRepository->find($resourceType->id);

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

        $this->resourceTypeRepository->find(self::NON_EXISTING_ID);
    }

    /**
     * @test
     * @covers ::all
     */
    public function testAll()
    {
        ResourceType::factory(5)->create();

        $resourceTypes = $this->resourceTypeRepository->all();

        $this->assertCount(5, $resourceTypes);
    }

    /**
     * @test
     * @covers ::create
     */
    public function testCreate()
    {
        $data = ResourceType::factory()->make()->toArray();

        $resourceType = $this->resourceTypeRepository->create($data);

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
        $this->resourceTypeRepository->update($newData, self::NON_EXISTING_ID);
    }

    /**
     * @test
     * @covers ::update
     */
    public function testUpdate()
    {
        $resourceType = ResourceType::factory()->create();
        $newData = ResourceType::factory()->make()->toArray();

        $updatedResourceType = $this->resourceTypeRepository->update($newData, $resourceType->id);

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

        $result = $this->resourceTypeRepository->delete($resourceType->id);

        $this->assertTrue($result);

        $this->expectException(ModelNotFoundException::class);
        $foundResourceType = $this->resourceTypeRepository->find($resourceType->id);
        $this->assertDatabaseMissing($resourceType->getTable(), [
            'id' => $resourceType->id
        ]);
    }
}
