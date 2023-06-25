<?php

namespace App\Lib\Exceptions;

class ServiceNotFoundException extends AbstractRpcResponseError
{
    public static function fromServiceName(string $serviceName, \Throwable $previous = null): self
    {
        return new ServiceNotFoundException(
            sprintf('Service "%s" not found', $serviceName),
            -32700,
            $previous
        );
    }
}
