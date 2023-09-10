<?php

namespace Tests\Feature\Services;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Services\UserService
 */
class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(UserService::class);
    }

    /**
     * @test
     * @covers ::getUserById
     */
    public function testGetUserById()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $foundUser = $this->service->getUserById($user->id);

        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->name, $foundUser->name);
        $this->assertEquals($user->email, $foundUser->email);
    }

    /**
     * @test
     * @covers ::getAllUsers
     */
    public function testGetAllUsers()
    {
        User::factory(5)->create();

        $users = $this->service->getAllUsers();

        $this->assertCount(5, $users);
    }

    /**
     * @test
     * @covers ::createUser
     */
    public function testCreate()
    {
        /** @var User $model */
        $model = User::factory()->make();
        $model->makeVisible($model->getAttributes());
        $data = $model->getAttributes();

        $user = $this->service->createUser($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
        $this->assertDatabaseHas($user->getTable(), [
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);
    }

    /**
     * @test
     * @covers ::updateUser
     */
    public function testUpdate()
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var User $newUser */
        $newUser = User::factory()->make();
        $newUser->makeVisible($newUser->getAttributes());
        $newData = $newUser->getAttributes();
        $newData['email_verified_at'] = $newData['email_verified_at']->toDateTimeString();

        $updatedUser = $this->service->updateUser($newData, $user->id);

        $this->assertInstanceOf(User::class, $updatedUser);
        $this->assertEquals($newData['name'], $updatedUser->name);
        $this->assertEquals($newData['email'], $updatedUser->email);
        $this->assertDatabaseHas($user->getTable(), [
            'id'    => $user->id,
            'name'  => $newData['name'],
            'email' => $newData['email'],
        ]);
    }

    /**
     * @test
     * @covers ::deleteUser
     */
    public function testDelete()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $result = $this->service->deleteUser($user->id);

        $this->assertTrue($result);

        $this->expectException(ModelNotFoundException::class);
        $this->service->getUserById($user->id);
        $this->assertDatabaseMissing($user->getTable(), [
            'id' => $user->id
        ]);
    }
}
