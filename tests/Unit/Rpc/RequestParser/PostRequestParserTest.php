<?php

namespace App\Rpc\RequestParser;

use App\Lib\Exceptions\InvalidRequestException;
use App\Rpc\MethodCallDTO;
use PHPUnit\Framework\TestCase;
use Swoole\Http\Request;

class PostRequestParserTest extends TestCase
{
    private PostRequestParser $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new PostRequestParser();
    }

    public function testWrongRpcVersionRequest()
    {
        $this->expectException(InvalidRequestException::class);

        $requestMock = $this->createMock(Request::class);
        $requestMock->method('getContent')->willReturn(
            $this->buildBodyRequest('1.0','some-method',1, [])
        );

        $this->service->parse($requestMock);
    }

    public function testWrongRpcMethodRequest()
    {
        $this->expectException(InvalidRequestException::class);

        $requestMock = $this->createMock(Request::class);
        $requestMock->method('getContent')->willReturn(
            $this->buildBodyRequest('2.0','',1, [])
        );

        $this->service->parse($requestMock);
    }

    public function testRequest()
    {
        $requestMock = $this->createMock(Request::class);
        $requestMock->method('getContent')->willReturn(
            $this->buildBodyRequest(
                '2.0',
                'some-method',
                10,
                ['some' =>'parameter']
            )
        );

        $result = $this->service->parse($requestMock);

        $this->assertInstanceOf(MethodCallDTO::class, $result);
        $this->assertEquals('2.0', $result->jsonrpc);
        $this->assertEquals('some-method', $result->method);
        $this->assertEquals(10, $result->id);
        $this->assertEquals(['some' =>'parameter'], $result->params);
    }

    private function buildBodyRequest(
        string $jsonrpc,
        string $method,
        int    $id = 1,
        array  $params = []
    ): string
    {
        return json_encode([
            'jsonrpc' => $jsonrpc,
            'method' => $method,
            'id' => $id,
            'params' => $params
        ]);
    }
}
