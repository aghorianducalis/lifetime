<?php

namespace Tests\Feature\Controllers;

use App\Models\Coordinate;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\CoordinateController
 */
class CoordinateControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    /**
     * @test
     * @covers ::index
     */
    public function test_user_can_get_related_coordinates()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Collection $coordinates */
        $coordinates = Coordinate::factory(3)->withUsers([$user->id])->create();

        $response = $this->actingAs($user)->get(route('coordinates.index'));

        $response->assertOk();
        $response->assertJsonCount($coordinates->count(), 'data');
        $response->assertJsonStructure(['data' => [$this->getRequiredResponseFields()]]);
        $this->assertEquals($coordinates->pluck('id')->toArray(), $response->json('data.*.id'));
    }

    /**
     * @test
     * @covers ::index
     */
    public function test_cannot_get_forbidden_coordinates()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        Coordinate::factory(3)->create();

        $response = $this->actingAs($user)->get(route('coordinates.index'));

        $response->assertOk();
        $response->assertJsonCount(0, 'data');
    }

    /**
     * @test
     * @covers ::store
     */
    public function test_create()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        $coordinateData = Coordinate::factory()->make()->toArray();

        $response = $this->actingAs($user)->postJson(route('coordinates.store'), $coordinateData);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure(['data' => $this->getRequiredResponseFields()]);
        $response->assertJsonFragment($coordinateData);
        $this->assertDatabaseHas((new Coordinate())->getTable(), ['id' => $response->json('data.id')]);
    }

    /**
     * @test
     * @covers ::show
     */
    public function test_user_can_get_related_coordinate()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->withUsers([$user->id])->create();

        $response = $this->actingAs($user)->get(route('coordinates.show', $coordinate->id));

        $response->assertOk();
        $response->assertJsonStructure(['data' => $this->getRequiredResponseFields()]);
        $response->assertJsonFragment(['id' => $coordinate->id]);
    }

    /**
     * @test
     * @covers ::show
     */
    public function test_user_cannot_get_forbidden_coordinate()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->create();

        $response = $this->actingAs($user)->get(route('coordinates.show', $coordinate->id));
        $response->assertForbidden();
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_user_can_update_related_coordinate()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->withUsers([$user->id])->create();
        $updatedData = Coordinate::factory()->make()->only([
            'x',
            'y',
            'z',
            't',
        ]);
        $updatedData['t'] = $updatedData['t']->toDateTimeString();

        $response = $this->actingAs($user)->putJson(route('coordinates.update', $coordinate->id), $updatedData);

        $response->assertOk();
        $response->assertJsonStructure(['data' => $this->getRequiredResponseFields()]);
        $response->assertJsonFragment($updatedData);
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_user_cannot_update_forbidden_coordinate()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->create();
        $updatedData = Coordinate::factory()->make()->only([
            'x',
            'y',
            'z',
            't',
        ]);
        $updatedData['t'] = $updatedData['t']->toDateTimeString();

        $response = $this->actingAs($user)->putJson(route('coordinates.update', $coordinate->id), $updatedData);
        $response->assertForbidden();
    }

    /**
     * @test
     * @covers ::destroy
     */
    public function test_can_destroy_related_coordinate()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->withUsers([$user->id])->create();

        $response = $this->actingAs($user)->deleteJson(route('coordinates.destroy', $coordinate->id));

        $response->assertOk();
        $response->assertExactJson(['result' => true]);

        $this->assertDatabaseMissing((new Coordinate())->getTable(), ['id' => $coordinate->id]);
    }

    /**
     * @test
     * @covers ::destroy
     */
    public function test_cannot_destroy_forbidden_coordinate()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->create();

        $response = $this->actingAs($user)->deleteJson(route('coordinates.destroy', $coordinate->id));
        $response->assertForbidden();
    }

    private function getRequiredResponseFields(): array
    {
        return [
            'id',
            'x',
            'y',
            'z',
            't',
            'created_at',
            'updated_at',
        ];
    }
}
