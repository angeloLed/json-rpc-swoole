<?php

namespace App\Lib\Exceptions;

class NotFoundException extends AbstractRpcResponseError
{
    public static function fromRpcMethod(string $rpcMethod, \Throwable $previous = null): self
    {
        return new NotFoundException(
            sprintf('RPC method "%s" is not found', $rpcMethod),
            -32601,
            $previous
        );
    }
}
