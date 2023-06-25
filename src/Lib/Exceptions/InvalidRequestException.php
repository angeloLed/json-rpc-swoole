<?php

namespace App\Lib\Exceptions;

class InvalidRequestException extends AbstractRpcResponseError
{
    public static function fromContentType(string $contentType, \Throwable $previous = null): self
    {
        return new InvalidRequestException(
            sprintf('Content-type "%s" is not valid', $contentType),
            -32700,
            $previous
        );
    }

    public static function fromBody(\Throwable $previous = null): self
    {
        return new InvalidRequestException('Body request is not valid', -32600, $previous);
    }

    public static function fromHttpMethod(string $httpMethod, \Throwable $previous = null): self
    {
        return new InvalidRequestException(
            sprintf('HTTP method "%s" is not valid', $httpMethod),
            -32700,
            $previous
        );
    }
}
