<?php

namespace Tests\Feature\Services;

use App\Models\Event;
use App\Repositories\EventRepository;
use App\Services\EventService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Services\EventService
 */
class EventServiceTest extends TestCase
{
    use RefreshDatabase;

    protected EventService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $eventRepository = app(EventRepository::class);
        $this->service = new EventService($eventRepository);
    }

    /**
     * @test
     * @covers ::getEventById
     */
    public function testGetEventById()
    {
        /** @var Event $event */
        $event = Event::factory()->create();

        $foundEvent = $this->service->getEventById($event->id);

        $this->assertInstanceOf(Event::class, $foundEvent);
        $this->assertEquals($event->title, $foundEvent->title);
        $this->assertEquals($event->description, $foundEvent->description);
    }

    /**
     * @test
     * @covers ::getAllEvents
     */
    public function testGetAllEvents()
    {
        Event::factory(5)->create();

        $events = $this->service->getAllEvents();

        $this->assertCount(5, $events);
    }

    /**
     * @test
     * @covers ::createEvent
     */
    public function testCreate()
    {
        $data = Event::factory()->make()->toArray();

        $event = $this->service->createEvent($data);

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
     * @covers ::updateEvent
     */
    public function testUpdate()
    {
        $event = Event::factory()->create();
        $newData = Event::factory()->make()->toArray();

        $updatedEvent = $this->service->updateEvent($newData, $event->id);

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
     * @covers ::deleteEvent
     */
    public function testDelete()
    {
        $event = Event::factory()->create();

        $result = $this->service->deleteEvent($event->id);

        $this->assertTrue($result);

        $this->expectException(ModelNotFoundException::class);
        $this->service->getEventById($event->id);
        $this->assertDatabaseMissing($event->getTable(), [
            'id' => $event->id
        ]);
    }
}
