<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Models\ResourceType;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\ResourceTypeController
 */
class ResourceTypeControllerTest extends TestCase
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
    public function test_user_can_get_related_resource_types()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var Collection $resourceTypes */
        $resourceTypes = ResourceType::factory(3)->withUsers([$user->id])->create();

        $response = $this->actingAs($user)->get(route('resource-types.index'));

        $response->assertOk();
        $response->assertJsonCount($resourceTypes->count(), 'data');
        $response->assertJsonStructure(['data' => [$this->getRequiredResponseFields()]]);
        $this->assertEquals($resourceTypes->pluck('id')->toArray(), $response->json('data.*.id'));
    }

    /**
     * @test
     * @covers ::index
     */
    public function test_user_cannot_get_forbidden_resource_types()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        ResourceType::factory(3)->create();

        $response = $this->actingAs($user)->get(route('resource-types.index'));

        $response->assertOk();
        $response->assertJsonCount(0, 'data');
    }

    /**
     * @test
     * @covers ::store
     */
    public function test_create_resource_type()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        $resourceTypeData = ResourceType::factory()->make()->toArray();

        $response = $this->actingAs($user)->postJson(route('resource-types.store'), $resourceTypeData);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure(['data' => $this->getRequiredResponseFields()]);
        $response->assertJsonFragment($resourceTypeData);
        $this->assertDatabaseHas((new ResourceType())->getTable(), ['id' => $response->json('data.id')]);
    }

    /**
     * @test
     * @covers ::show
     */
    public function test_user_can_get_related_resource_type()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        $resourceType = ResourceType::factory()->withUsers([$user->id])->create();

        $response = $this->actingAs($user)->get(route('resource-types.show', $resourceType->id));

        $response->assertOk();
        $response->assertJsonStructure(['data' => $this->getRequiredResponseFields()]);
        $response->assertJsonFragment(['id' => $resourceType->id]);
    }

    /**
     * @test
     * @covers ::show
     */
    public function test_user_cannot_get_forbidden_resource_type()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        $resourceType = ResourceType::factory()->create();

        $response = $this->actingAs($user)->get(route('resource-types.show', $resourceType->id));
        $response->assertForbidden();
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_user_can_update_related_resource_type()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        $resourceType = ResourceType::factory()->withUsers([$user->id])->create();
        $updatedData = ResourceType::factory()->make()->only([
            'title',
            'description',
        ]);

        $response = $this->actingAs($user)->putJson(route('resource-types.update', $resourceType->id), $updatedData);

        $response->assertOk();
        $response->assertJsonStructure(['data' => $this->getRequiredResponseFields()]);
        $response->assertJsonFragment($updatedData);
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_user_cannot_update_forbidden_resource_type()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        $resourceType = ResourceType::factory()->create();
        $updatedData = ResourceType::factory()->make()->only([
            'title',
            'description',
        ]);

        $response = $this->actingAs($user)->putJson(route('resource-types.update', $resourceType->id), $updatedData);
        $response->assertForbidden();
    }

    /**
     * @test
     * @covers ::destroy
     */
    public function test_can_destroy_related_resource_type()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::factory()->withUsers([$user->id])->create();

        $response = $this->actingAs($user)->deleteJson(route('resource-types.destroy', $resourceType->id));

        $response->assertOk();
        $response->assertExactJson(['result' => true]);

        $this->assertDatabaseMissing((new ResourceType())->getTable(), ['id' => $resourceType->id]);
    }

    /**
     * @test
     * @covers ::destroy
     */
    public function test_cannot_destroy_forbidden_resource_type()
    {
        /** @var User $user */
        $user = User::factory()->user()->create();

        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::factory()->create();

        $response = $this->actingAs($user)->deleteJson(route('resource-types.destroy', $resourceType->id));
        $response->assertForbidden();
    }

    private function getRequiredResponseFields(): array
    {
        return [
            'id',
            'title',
            'description',
            'created_at',
            'updated_at',
        ];
    }
}
