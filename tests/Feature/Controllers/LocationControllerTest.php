<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Models\Coordinate;
use App\Models\Location;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\LocationController
 */
class LocationControllerTest extends TestCase
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
    public function test_user_can_get_related_locations()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();
        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->withUsers([$user->id])->create();
        /** @var Location|Collection $locations */
        $locations = Location::factory()->create(['coordinate_id' => $coordinate->id]);

        $response = $this->actingAs($user)->get(route('locations.index'));

        $response->assertOk();
        $response->assertJsonCount($locations->count(), 'data');
        $response->assertJsonStructure(['data' => [$this->getRequiredResponseFields()]]);
        $this->assertEquals($locations->pluck('id')->toArray(), $response->json('data.*.id'));
    }

    /**
     * @test
     * @covers ::index
     */
    public function test_user_cannot_get_forbidden_locations()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        Location::factory(3)->create();

        $response = $this->actingAs($user)->get(route('locations.index'));

        $response->assertOk();
        $response->assertJsonCount(0, 'data');
    }

    /**
     * @test
     * @covers ::store
     */
    public function test_create_location()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        $locationData = Location::factory()->make()->toArray();

        $response = $this->actingAs($user)->postJson(route('locations.store'), $locationData);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure(['data' => $this->getRequiredResponseFields()]);
        $response->assertJsonFragment($locationData);
        $this->assertDatabaseHas((new Location())->getTable(), ['id' => $response->json('data.id')]);
    }

    /**
     * @test
     * @covers ::show
     */
    public function test_user_can_get_related_location()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();
        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->withUsers([$user->id])->create();
        /** @var Location $location */
        $location = Location::factory()->create(['coordinate_id' => $coordinate->id]);

        $response = $this->actingAs($user)->get(route('locations.show', $location->id));

        $response->assertOk();
        $response->assertJsonStructure(['data' => $this->getRequiredResponseFields()]);
        $response->assertJsonFragment(['id' => $location->id]);
    }

    /**
     * @test
     * @covers ::show
     */
    public function test_user_cannot_get_forbidden_location()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Location $location */
        $location = Location::factory()->create();

        $response = $this->actingAs($user)->get(route('locations.show', $location->id));
        $response->assertForbidden();
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_user_can_update_related_location()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();
        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->withUsers([$user->id])->create();
        /** @var Location $location */
        $location = Location::factory()->create(['coordinate_id' => $coordinate->id]);
        $updatedData = Location::factory()->make()->only([
            'title',
            'description',
            'coordinate_id',
        ]);

        $response = $this->actingAs($user)->putJson(route('locations.update', $location->id), $updatedData);

        $response->assertOk();
        $response->assertJsonStructure(['data' => $this->getRequiredResponseFields()]);
        $response->assertJsonFragment($updatedData);
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_user_cannot_update_forbidden_location()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();
        /** @var Location $location */
        $location = Location::factory()->create();
        $updatedData = Location::factory()->make()->only([
            'title',
            'description',
            'coordinate_id',
        ]);

        $response = $this->actingAs($user)->putJson(route('locations.update', $location->id), $updatedData);
        $response->assertForbidden();
    }

    /**
     * @test
     * @covers ::destroy
     */
    public function test_can_destroy_related_location()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();
        /** @var Coordinate $coordinate */
        $coordinate = Coordinate::factory()->withUsers([$user->id])->create();
        /** @var Location $location */
        $location = Location::factory()->create(['coordinate_id' => $coordinate->id]);

        $response = $this->actingAs($user)->deleteJson(route('locations.destroy', $location->id));

        $response->assertOk();
        $response->assertExactJson(['result' => true]);
        $this->assertDatabaseMissing((new Location())->getTable(), ['id' => $location->id]);
    }

    /**
     * @test
     * @covers ::destroy
     */
    public function test_cannot_destroy_forbidden_location()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();
        /** @var Location $location */
        $location = Location::factory()->create();

        $response = $this->actingAs($user)->deleteJson(route('locations.destroy', $location->id));
        $response->assertForbidden();
    }

    private function getRequiredResponseFields(): array
    {
        return [
            'id',
            'title',
            'description',
            'coordinate_id',
            'created_at',
            'updated_at',
        ];
    }
}
