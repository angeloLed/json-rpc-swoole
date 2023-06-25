<?php

namespace App\Lib\ServiceContainer;

use Psr\Container\ContainerInterface;

interface ServiceContainerInterface extends ContainerInterface
{
    public function set(string $id, object $service): void;
}
