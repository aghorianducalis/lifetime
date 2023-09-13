<?php

namespace Tests\Feature\Controllers;

use App\Models\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\ResourceController
 */
class ResourceControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     * @covers ::index
     */
    public function test_index()
    {
        Resource::factory(3)->create();

        $response = $this->get(route('resources.index'));

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
    }

    /**
     * @test
     * @covers ::store
     */
    public function test_create()
    {
        $resourceData = Resource::factory()->make()->toArray();

        $response = $this->postJson(route('resources.store'), $resourceData);

        $response->assertStatus(201)
            ->assertJsonFragment($resourceData);
    }

    /**
     * @test
     * @covers ::show
     */
    public function test_show()
    {
        $resource = Resource::factory()->create();

        $response = $this->get(route('resources.show', $resource->id));

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $resource->id]);
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_update()
    {
        $resource = Resource::factory()->create();
        $updatedData = Resource::factory()->make()->only([
            'amount',
            'resource_type_id',
        ]);

        $response = $this->putJson(route('resources.update', $resource->id), $updatedData);

        $response->assertStatus(200)
            ->assertJsonFragment($updatedData);
    }

    /**
     * @test
     * @covers ::destroy
     */
    public function test_destroy()
    {
        $resource = Resource::factory()->create();

        $response = $this->deleteJson(route('resources.destroy', $resource->id));

        $response->assertStatus(200)
            ->assertExactJson(['result' => true]);

        $this->assertDatabaseMissing('resources', ['id' => $resource->id]);
    }
}
