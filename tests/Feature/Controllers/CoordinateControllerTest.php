<?php

namespace Tests\Feature\Controllers;

use App\Models\Coordinate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\CoordinateController
 */
class CoordinateControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     * @covers ::index
     */
    public function test_index()
    {
        Coordinate::factory(3)->create();

        $response = $this->get(route('coordinates.index'));

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
    }

    /**
     * @test
     * @covers ::store
     */
    public function test_create()
    {
        $coordinateData = Coordinate::factory()->make()->toArray();

        $response = $this->postJson(route('coordinates.store'), $coordinateData);

        $response->assertStatus(201)
            ->assertJsonFragment($coordinateData);
    }

    /**
     * @test
     * @covers ::show
     */
    public function test_show()
    {
        $coordinate = Coordinate::factory()->create();

        $response = $this->get(route('coordinates.show', $coordinate->id));

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $coordinate->id]);
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_update()
    {
        $coordinate = Coordinate::factory()->create();
        $updatedData = Coordinate::factory()->make()->only([
            'x',
            'y',
            'z',
            't',
        ]);
        $updatedData['t'] = $updatedData['t']->toDateTimeString();

        $response = $this->putJson(route('coordinates.update', $coordinate->id), $updatedData);

        $response->assertStatus(200)
            ->assertJsonFragment($updatedData);
    }

    /**
     * @test
     * @covers ::destroy
     */
    public function test_destroy()
    {
        $coordinate = Coordinate::factory()->create();

        $response = $this->deleteJson(route('coordinates.destroy', $coordinate->id));

        $response->assertStatus(200)
            ->assertExactJson(['result' => true]);

        $this->assertDatabaseMissing('resource_types', ['id' => $coordinate->id]);
    }
}
