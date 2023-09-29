<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\User;
use App\Services\UserService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\UserController
 */
class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->admin = User::factory()->admin()->create();
    }

    /**
     * @test
     * @covers ::index
     */
    public function test_index()
    {
        /** @var Collection $resourceTypes */
        $users = User::factory(3)->create();

        $response = $this->actingAs($this->admin)->get(route('users.index'));

        $response->assertOk();
        $response->assertJsonCount($users->count() + 1, 'data');
        $response->assertJsonStructure(['data' => [$this->getRequiredResponseFields()]]);
    }

    /**
     * @test
     * @covers ::store
     */
    public function test_create()
    {
        $roleEnum = RoleEnum::User;
        $roleName = $roleEnum->value;
        $permissionNames = PermissionEnum::permissionsFromRoleEnum($roleEnum);
        $password = 'password';

        /** @var User $model */
        $model = User::factory()->make(['password' => $password]);
        $model->makeVisible($model->getAttributes());
        $data = $model->getAttributes();
        $data['email_verified_at'] = $data['email_verified_at']->toDateTimeString();
        $data['password_confirmation'] = $password;

        $response = $this->actingAs($this->admin)->postJson(route('users.store'), $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment(Arr::except($data, ['password', 'password_confirmation', 'remember_token']));
        $response->assertJsonStructure(['data' => $this->getRequiredResponseFields()]);

        /** @var UserService $service */
        $service = app(UserService::class);
        $createdUserFromDB = $service->getUserById($response->json('data.id'));

        $this->assertCount(1, $createdUserFromDB->roles);
        $this->assertEquals($roleName, $createdUserFromDB->roles->first()->name);
        $this->assertCount(sizeof($permissionNames), $createdUserFromDB->getPermissionsViaRoles());
        $this->assertEquals(
            collect($permissionNames)->pluck('value')->sort()->toArray(),
            $createdUserFromDB->getPermissionsViaRoles()->pluck('name')->sort()->toArray()
        );
    }

    /**
     * @test
     * @covers ::show
     */
    public function test_show()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('users.show', $user->id));

        $response->assertOk();
        $response->assertJsonStructure(['data' => $this->getRequiredResponseFields()]);
        $response->assertJsonFragment(['id' => $user->id]);
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_update()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $updatedData = User::factory()->make()->only([
            'name',
            'email',
            'password',
            'email_verified_at',
        ]);
        $updatedData['email_verified_at'] = $updatedData['email_verified_at']->toDateTimeString();

        $response = $this->actingAs($this->admin)->putJson(route('users.update', $user->id), $updatedData);

        $response->assertOk();
        $response->assertJsonStructure(['data' => $this->getRequiredResponseFields()]);
        $response->assertJsonFragment(Arr::except($updatedData, ['password']));
    }

    /**
     * @test
     * @covers ::destroy
     */
    public function test_destroy()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)->deleteJson(route('users.destroy', $user->id));

        $response->assertOk();
        $response->assertExactJson(['result' => true]);

        $this->assertDatabaseMissing('resource_types', ['id' => $user->id]);
    }

    private function getRequiredResponseFields(): array
    {
        return [
            'id',
            'name',
            'email',
            'email_verified_at',
            'created_at',
            'updated_at',
        ];
    }
}
