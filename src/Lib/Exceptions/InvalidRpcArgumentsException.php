<?php

namespace App\Lib\Exceptions;

class InvalidRpcArgumentsException extends AbstractRpcResponseError
{
    public static function fromMethod(string $rpcMethod, \Throwable $previous = null): self
    {
        return new InvalidRpcArgumentsException(
            sprintf('Invalid arguments for "%s" method', $rpcMethod),
            -32700,
            $previous
        );
    }

    public static function fromMethodAndTypeArguments(
        string $rpcMethod,
        \Throwable $previous = null
    ): self {
        return new InvalidRpcArgumentsException(
            sprintf(
                'Invalid arguments types for "%s" method: %s',
                $rpcMethod,
                $previous !== null ? $previous->getMessage() : ''
            ),
            -32602,
            $previous
        );
    }
}
