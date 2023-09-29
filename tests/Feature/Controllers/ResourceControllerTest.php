<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Models\Resource;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\ResourceController
 */
class ResourceControllerTest extends TestCase
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
    public function test_user_can_get_related_resources()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Collection $resources */
        $resources = Resource::factory(3)->withUsers([$user->id])->create();

        $response = $this->actingAs($user)->get(route('resources.index'));

        $response->assertOk();
        $response->assertJsonCount($resources->count(), 'data');
        $response->assertJsonStructure(['data' => [$this->getRequiredResponseFields()]]);
        $this->assertEquals($resources->pluck('id')->toArray(), $response->json('data.*.id'));
    }

    /**
     * @test
     * @covers ::index
     */
    public function test_cannot_get_forbidden_resources()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        Resource::factory(3)->create();

        $response = $this->actingAs($user)->get(route('resources.index'));

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

        $resourceData = Resource::factory()->make()->toArray();

        $response = $this->actingAs($user)->postJson(route('resources.store'), $resourceData);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure(['data' => $this->getRequiredResponseFields()]);
        $response->assertJsonFragment($resourceData);
        $this->assertDatabaseHas((new Resource())->getTable(), ['id' => $response->json('data.id')]);
    }

    /**
     * @test
     * @covers ::show
     */
    public function test_user_can_get_related_resource()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Resource $resource */
        $resource = Resource::factory()->withUsers([$user->id])->create();

        $response = $this->actingAs($user)->get(route('resources.show', $resource->id));

        $response->assertOk();
        $response->assertJsonStructure(['data' => $this->getRequiredResponseFields()]);
        $response->assertJsonFragment(['id' => $resource->id]);
    }

    /**
     * @test
     * @covers ::show
     */
    public function test_user_cannot_get_forbidden_resource()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Resource $resource */
        $resource = Resource::factory()->create();

        $response = $this->actingAs($user)->get(route('resources.show', $resource->id));
        $response->assertForbidden();
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_user_can_update_related_resource()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Resource $resource */
        $resource = Resource::factory()->withUsers([$user->id])->create();
        $updatedData = Resource::factory()->make()->only([
            'amount',
            'resource_type_id',
        ]);

        $response = $this->actingAs($user)->putJson(route('resources.show', $resource->id), $updatedData);

        $response->assertOk();
        $response->assertJsonStructure(['data' => $this->getRequiredResponseFields()]);
        $response->assertJsonFragment($updatedData);
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_user_cannot_update_forbidden_resource()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Resource $resource */
        $resource = Resource::factory()->create();
        $updatedData = Resource::factory()->make()->only([
            'amount',
            'resource_type_id',
        ]);

        $response = $this->actingAs($user)->putJson(route('resources.show', $resource->id), $updatedData);
        $response->assertForbidden();
    }

    /**
     * @test
     * @covers ::destroy
     */
    public function test_can_destroy_related_resource()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Resource $resource */
        $resource = Resource::factory()->withUsers([$user->id])->create();

        $response = $this->actingAs($user)->deleteJson(route('resources.destroy', $resource->id));

        $response->assertOk();
        $response->assertExactJson(['result' => true]);

        $this->assertDatabaseMissing((new Resource())->getTable(), ['id' => $resource->id]);
    }

    /**
     * @test
     * @covers ::destroy
     */
    public function test_cannot_destroy_forbidden_resource()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Resource $resource */
        $resource = Resource::factory()->create();

        $response = $this->actingAs($user)->deleteJson(route('resources.destroy', $resource->id));
        $response->assertForbidden();
    }

    private function getRequiredResponseFields(): array
    {
        return [
            'id',
            'amount',
            'resource_type_id',
            'created_at',
            'updated_at',
        ];
    }
}
