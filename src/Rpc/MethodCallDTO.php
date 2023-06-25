<?php

namespace App\Rpc;

class MethodCallDTO
{
    public function __construct(
        public readonly string $jsonrpc,
        public readonly string $method,
        public readonly ?array $params = null,
        public readonly string|int|null $id = null
    ) {
    }
}
