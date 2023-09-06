<?php

namespace Tests\Feature\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Repositories\UserRepository
 */
class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected UserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(UserRepository::class);
    }

    /**
     * @test
     * @covers ::find
     */
    public function testFind()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $foundUser = $this->repository->find($user->id);

        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->name, $foundUser->name);
        $this->assertEquals($user->email, $foundUser->email);
    }

    /**
     * @test
     * @covers ::find
     */
    public function testFindNonExistingResource()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->find($this->getRandomUuid());
    }

    /**
     * @test
     * @covers ::matching
     */
    public function testGetAll()
    {
        User::factory(5)->create();

        $users = $this->repository->matching();

        $this->assertCount(5, $users);
    }

    /**
     * @test
     * @covers ::create
     */
    public function testCreate()
    {
        /** @var User $model */
        $model = User::factory()->make();
        $model->makeVisible($model->getAttributes());
        $data = $model->getAttributes();

        $user = $this->repository->create($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
        $this->assertDatabaseHas($user->getTable(), [
            'name'  => $data['name'],
            'email' => $data['email']
        ]);
    }

    /**
     * @test
     * @covers ::update
     */
    public function testUpdateNonExistingUser()
    {
        $this->expectException(ModelNotFoundException::class);

        $newData = User::factory()->make()->toArray();
        $this->repository->update($newData, $this->getRandomUuid());
    }

    /**
     * @test
     * @covers ::update
     */
    public function testUpdate()
    {
        $user = User::factory()->create();
        $newData = User::factory()->make()->toArray();

        $updatedUser = $this->repository->update($newData, $user->id);

        $this->assertInstanceOf(User::class, $updatedUser);
        $this->assertEquals($newData['name'], $updatedUser->name);
        $this->assertEquals($newData['email'], $updatedUser->email);
        $this->assertDatabaseHas($user->getTable(), [
            'id'    => $user->id,
            'name'  => $newData['name'],
            'email' => $newData['email']
        ]);
    }

    /**
     * @test
     * @covers ::delete
     */
    public function testDelete()
    {
        $user = User::factory()->create();

        $result = $this->repository->delete($user->id);

        $this->assertTrue($result);

        $this->expectException(ModelNotFoundException::class);
        $foundUser = $this->repository->find($user->id);
        $this->assertDatabaseMissing($user->getTable(), [
            'id' => $user->id
        ]);
    }
}
