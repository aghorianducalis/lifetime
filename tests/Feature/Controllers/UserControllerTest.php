<?php

namespace Tests\Feature\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\User;
use App\Services\UserService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\UserController
 */
class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     * @covers ::index
     */
    public function test_index()
    {
        User::factory(3)->create();

        $response = $this->get(route('users.index'));

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
    }

    /**
     * @test
     * @covers ::store
     */
    public function test_create()
    {
        $this->seed(RolePermissionSeeder::class);
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

        $response = $this->postJson(route('users.store'), $data);

        $response->assertStatus(201)
            ->assertJsonFragment(Arr::except($data, ['password', 'password_confirmation', 'remember_token']));

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
        $user = User::factory()->create();

        $response = $this->get(route('users.show', $user->id));

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $user->id]);
    }

    /**
     * @test
     * @covers ::update
     */
    public function test_update()
    {
        $user = User::factory()->create();
        $updatedData = User::factory()->make()->only([
            'name',
            'email',
            'password',
            'email_verified_at',
        ]);
        $updatedData['email_verified_at'] = $updatedData['email_verified_at']->toDateTimeString();

        $response = $this->putJson(route('users.update', $user->id), $updatedData);

        $response->assertStatus(200)
            ->assertJsonFragment(Arr::except($updatedData, ['password']));
    }

    /**
     * @test
     * @covers ::destroy
     */
    public function test_destroy()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson(route('users.destroy', $user->id));

        $response->assertStatus(200)
            ->assertExactJson(['result' => true]);

        $this->assertDatabaseMissing('resource_types', ['id' => $user->id]);
    }
}
