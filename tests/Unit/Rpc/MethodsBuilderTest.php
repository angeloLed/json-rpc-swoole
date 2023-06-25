<?php

namespace App\Rpc;

use App\Lib\Exceptions\InvalidRequestException;
use App\Rpc\MethodCallDTO;
use App\Rpc\RequestParser\GetRequestParser;
use PHPUnit\Framework\TestCase;
use Swoole\Http\Request;

class MethodsBuilderTest extends TestCase
{
    public function test()
    {
        $dto = new MethodCallDTO(
            $jsonrpc = uniqid(),
            $method = uniqid(),
            $params = ['param1' => 1, 'param2' => 2],
            $id = random_int(1,10)
        );

        $this->assertEquals($jsonrpc, $dto->jsonrpc);
        $this->assertEquals($method, $dto->method);
        $this->assertEquals($params, $dto->params);
        $this->assertEquals($id, $dto->id);
    }
}
