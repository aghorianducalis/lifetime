<?php

namespace Tests\Feature\Controllers;

use App\Models\ResourceType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\ResourceTypeController
 */
class ResourceTypeControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     * @covers ::index
     */
    public function testIndex()
    {
        ResourceType::factory(3)->create();

        $response = $this->get(route('resource-types.index'));

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
    }

    /**
     * @test
     * @covers ::store
     */
    public function testCreate()
    {
        $resourceTypeData = ResourceType::factory()->make()->toArray();

        $response = $this->postJson(route('resource-types.store'), $resourceTypeData);

        $response->assertStatus(201)
            ->assertJsonFragment($resourceTypeData);
    }

    /**
     * @test
     * @covers ::show
     */
    public function testShow()
    {
        $resourceType = ResourceType::factory()->create();

        $response = $this->get(route('resource-types.show', $resourceType->id));

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $resourceType->id]);
    }

    /**
     * @test
     * @covers ::update
     */
    public function testUpdate()
    {
        $resourceType = ResourceType::factory()->create();
        $updatedData = ResourceType::factory()->make()->only([
            'title',
            'description',
        ]);

        $response = $this->putJson(route('resource-types.update', $resourceType->id), $updatedData);

        $response->assertStatus(200)
            ->assertJsonFragment($updatedData);
    }

    /**
     * @test
     * @covers ::destroy
     */
    public function testDestroy()
    {
        $resourceType = ResourceType::factory()->create();

        $response = $this->deleteJson(route('resource-types.destroy', $resourceType->id));

        $response->assertStatus(200)
            ->assertExactJson(['result' => true]);

        $this->assertDatabaseMissing('resource_types', ['id' => $resourceType->id]);
    }
}
