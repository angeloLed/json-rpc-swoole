<?php

namespace App\Lib\ServiceContainer;

use App\Lib\Exceptions\ServiceNotFoundException;
use PHPUnit\Framework\TestCase;

class ServiceContainerTest extends TestCase
{
    public function testGetException()
    {
        $this->expectException(ServiceNotFoundException::class);

        $serviceContainer = new ServiceContainer();

        $serviceContainer->get('not-exist-service');
    }

    public function testSetGet()
    {
        $serviceContainer = new ServiceContainer();

        $serviceContainer->set($serviceName = uniqid(), $service = new class () {});
        $serviceContainer->set(uniqid(), new class () {});
        $serviceContainer->set(uniqid(), new class () {});

        $this->assertSame(
            $service,
            $serviceContainer->get($serviceName)
        );
    }

    public function testHasWithExistService()
    {
        $serviceContainer = new ServiceContainer();

        $serviceContainer->set($serviceName = uniqid(), $service = new class () {});

        $this->assertTrue(
            $serviceContainer->has($serviceName)
        );
    }

    public function testHasWithNotExistService()
    {
        $serviceContainer = new ServiceContainer();

        $serviceContainer->set($serviceName = uniqid(), $service = new class () {});

        $this->assertFalse(
            $serviceContainer->has(uniqid())
        );
    }
}
