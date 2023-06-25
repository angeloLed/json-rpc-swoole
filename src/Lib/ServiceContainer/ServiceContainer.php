<?php

namespace App\Lib\ServiceContainer;

use App\Lib\Exceptions\ServiceNotFoundException;

class ServiceContainer implements ServiceContainerInterface
{
    /** array<string, mixed> */
    private array $services = [];

    public function set(string $id, object $service): void
    {
        $this->services[$id] = $service;
    }

    /**
     * @throws ServiceNotFoundException
     */
    public function get(string $id): object
    {
        if (!$this->has($id)) {
            throw ServiceNotFoundException::fromServiceName($id);
        }

        return $this->services[$id];
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }
}
