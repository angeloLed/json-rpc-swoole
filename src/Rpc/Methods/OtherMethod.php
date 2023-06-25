<?php

namespace App\Rpc\Methods;

class OtherMethod extends AbstractRpcMethod
{
    public static function getMethodName(): string
    {
        return 'otherMethod';
    }

    public function __invoke(mixed ...$params): array
    {
        return $params;
    }

    public function parseRpcArguments(array $arguments): mixed
    {
        return null;
    }
}
