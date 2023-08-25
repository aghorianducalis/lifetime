<?php

namespace Tests\Feature\Repositories;

use App\Models\Event;
use App\Repositories\EventRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Repositories\EventRepository
 */
class EventRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected EventRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EventRepository();
    }

    /**
     * @test
     * @covers ::find
     */
    public function testFind()
    {
        /** @var Event $event */
        $event = Event::factory()->create();

        $foundEvent = $this->repository->find($event->id);

        $this->assertInstanceOf(Event::class, $foundEvent);
        $this->assertEquals($event->title, $foundEvent->title);
        $this->assertEquals($event->description, $foundEvent->description);
    }

    /**
     * @test
     * @covers ::find
     */
    public function testFindNonExistingResource()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->find(self::NON_EXISTING_ID);
    }

    /**
     * @test
     * @covers ::matching
     */
    public function testGetList()
    {
        Event::factory(5)->create();

        $events = $this->repository->matching();

        $this->assertCount(5, $events);
    }

    /**
     * @test
     * @covers ::create
     */
    public function testCreate()
    {
        $data = Event::factory()->make()->toArray();

        $event = $this->repository->create($data);

        $this->assertInstanceOf(Event::class, $event);
        $this->assertEquals($data['title'], $event->title);
        $this->assertEquals($data['description'], $event->description);
        $this->assertDatabaseHas($event->getTable(), [
            'title'       => $data['title'],
            'description' => $data['description']
        ]);
    }

    /**
     * @test
     * @covers ::update
     */
    public function testUpdateNonExistingResource()
    {
        $this->expectException(ModelNotFoundException::class);

        $newData = Event::factory()->make()->toArray();
        $this->repository->update($newData, self::NON_EXISTING_ID);
    }

    /**
     * @test
     * @covers ::update
     */
    public function testUpdate()
    {
        $event = Event::factory()->create();
        $newData = Event::factory()->make()->toArray();

        $updatedEvent = $this->repository->update($newData, $event->id);

        $this->assertInstanceOf(Event::class, $updatedEvent);
        $this->assertEquals($newData['title'], $updatedEvent->title);
        $this->assertEquals($newData['description'], $updatedEvent->description);
        $this->assertDatabaseHas($event->getTable(), [
            'id'          => $event->id,
            'title'       => $newData['title'],
            'description' => $newData['description']
        ]);
    }

    /**
     * @test
     * @covers ::delete
     */
    public function testDelete()
    {
        $event = Event::factory()->create();

        $result = $this->repository->delete($event->id);

        $this->assertTrue($result);

        $this->expectException(ModelNotFoundException::class);
        $foundEvent = $this->repository->find($event->id);
        $this->assertDatabaseMissing($event->getTable(), [
            'id' => $event->id
        ]);
    }
}
