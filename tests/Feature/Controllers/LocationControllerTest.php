<?php

namespace Tests\Feature\Controllers;

use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\LocationController
 */
class LocationControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     * @covers ::index
     */
    public function test_index()
    {
        Location::factory(3)->create();

        $response = $this->get(route('locations.index'));

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
    }

    /**
     * @test
     * @covers ::store
     */
    public function test_create()
    {
        $locationData = Location::factory()->make()->toArray();

        $response = $this->postJson(route('locations.store'), $locationData);

        $response->assertStatus(201)
            ->assertJsonFragment($locationData);
    }

    /**
     * @test
     * @covers ::show
     */
    public function test_show()
    {
        $location = Location::factory()->create();

        $response = $this->get(route('locations.show', $location->id));

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $location->id]);
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_update()
    {
        $location = Location::factory()->create();
        $updatedData = Location::factory()->make()->only([
            'title',
            'description',
            'coordinate_id',
        ]);

        $response = $this->putJson(route('locations.update', $location->id), $updatedData);

        $response->assertStatus(200)
            ->assertJsonFragment($updatedData);
    }

    /**
     * @test
     * @covers ::destroy
     */
    public function test_destroy()
    {
        $location = Location::factory()->create();

        $response = $this->deleteJson(route('locations.destroy', $location->id));

        $response->assertStatus(200)
            ->assertExactJson(['result' => true]);

        $this->assertDatabaseMissing('resource_types', ['id' => $location->id]);
    }
}
