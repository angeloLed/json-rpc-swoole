<?php

namespace App\Lib\Exceptions;

class RpcMethodException extends AbstractRpcResponseError
{
    public static function fromMethod(string $rpcMethod, \Throwable $previous = null): self
    {
        return new RpcMethodException(
            sprintf('"%s" is not an RPC method', $rpcMethod),
            -32700,
            $previous
        );
    }
}
