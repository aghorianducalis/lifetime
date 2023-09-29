<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Services\RolePermissionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Services\RolePermissionService
 */
class RolePermissionServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @covers ::getInstance
     */
    public function test_get_instance()
    {
        $service = RolePermissionService::getInstance();

        $this->assertInstanceOf(RolePermissionService::class, $service);

        $service2 = RolePermissionService::getInstance();

        $this->assertInstanceOf(RolePermissionService::class, $service2);
        $this->assertSame($service, $service2);
    }
}
