<?php

namespace App\Rpc\RequestParser;

use App\Lib\Exceptions\InvalidRequestException;
use App\Rpc\MethodCallDTO;
use Swoole\Http\Request;

class GetRequestParser implements RequestParserInterface
{
    /**
     * @throws InvalidRequestException
     */
    public function parse(Request $request): MethodCallDTO
    {
        $jsonrpc = $request->get['jsonrpc'] ?? '';
        $rpcMethod = $request->get['method'] ?? '';
        $params = $request->get['params'] ?? null;
        $id = $request->get['id'] ?? null;

        if ($jsonrpc !== '2.0' || $rpcMethod === '') {
            throw InvalidRequestException::fromBody();
        }

        return new MethodCallDTO(
            $jsonrpc,
            $rpcMethod,
            json_decode(base64_decode($params), true),
            $id
        );
    }
}
