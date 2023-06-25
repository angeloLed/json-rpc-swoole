<?php

namespace App\Rpc\Methods;

use App\Transformer\TransformerInterface;

/**
 * This Abstracts it is to be considered as an APi Controller
 */
abstract class AbstractRpcMethod
{
    abstract public function parseRpcArguments(array $arguments): mixed;

    abstract public static function getMethodName(): string;
}
