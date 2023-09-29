<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\Event;
use App\Models\User;
use App\Services\CoordinateService;
use App\Services\EventService;
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
        $this->service = app(EventService::class);
    }

    /**
     * @test
     * @covers ::getEventById
     */
    public function test_get_event_by_id()
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
    public function test_get_all_events()
    {
        Event::factory(5)->create();

        $events = $this->service->getAllEvents();

        $this->assertCount(5, $events);
    }

    /**
     * @test
     * @covers ::getEventsByUser
     */
    public function test_get_events_by_user()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Event $event */
        $event = Event::factory()->withUsers([$user->id])->create();

        $foundEvents = $this->service->getEventsByUser($user->id);

        $this->assertCount(1, $foundEvents);
        /** @var Event $foundEvent */
        $foundEvent = $foundEvents->first();
        $this->assertEquals($event->id, $foundEvent->id);
        $this->assertEquals($user->id, $foundEvent->users->first()->id);
    }

    /**
     * @test
     * @covers ::doesEventBelongToUser
     */
    public function test_does_event_belong_to_user()
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var User $anotherUser */
        $anotherUser = User::factory()->create();
        /** @var Event $event */
        $event = Event::factory()->withUsers([$user->id])->create();

        $result = $this->service->doesEventBelongToUser($event->id, $user->id);

        $this->assertTrue($result);

        $result = $this->service->doesEventBelongToUser($event->id, $anotherUser->id);

        $this->assertFalse($result);
    }

    /**
     * @test
     * @covers ::createEvent
     */
    public function test_create()
    {
        /** @var Event $event */
        $event = Event::factory()->make();

        $createdEvent = $this->service->createEvent($event->toArray());

        $this->assertInstanceOf(Event::class, $createdEvent);
        $this->assertEquals($event->title, $createdEvent->title);
        $this->assertEquals($event->description, $createdEvent->description);
        $this->assertDatabaseHas($event->getTable(), [
            'title'       => $event->title,
            'description' => $event->description,
        ]);
    }

    /**
     * @test
     * @covers ::updateEvent
     */
    public function test_update()
    {
        /** @var Event $event */
        $event = Event::factory()->create();
        /** @var Event $newEvent */
        $newEvent = Event::factory()->make();

        $updatedEvent = $this->service->updateEvent($newEvent->toArray(), $event->id);

        $this->assertInstanceOf(Event::class, $updatedEvent);
        $this->assertEquals($newEvent->title, $updatedEvent->title);
        $this->assertEquals($newEvent->description, $updatedEvent->description);
        $this->assertDatabaseHas($event->getTable(), [
            'id'          => $event->id,
            'title'       => $newEvent->title,
            'description' => $newEvent->description,
        ]);
    }

    /**
     * @test
     * @covers ::deleteEvent
     */
    public function test_delete()
    {
        /** @var Event $event */
        $event = Event::factory()->create();

        $result = $this->service->deleteEvent($event->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing($event->getTable(), [
            'id' => $event->id,
        ]);
    }

    /**
     * @test
     * @covers ::getInstance
     */
    public function test_get_instance()
    {
        $service = CoordinateService::getInstance();

        $this->assertInstanceOf(CoordinateService::class, $service);

        $service2 = CoordinateService::getInstance();

        $this->assertInstanceOf(CoordinateService::class, $service2);
        $this->assertSame($service, $service2);
    }
}
