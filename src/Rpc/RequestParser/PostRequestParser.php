<?php

namespace App\Rpc\RequestParser;

use App\Lib\Exceptions\InvalidRequestException;
use App\Rpc\MethodCallDTO;
use Swoole\Http\Request;

class PostRequestParser implements RequestParserInterface
{
    /**
     * @throws InvalidRequestException
     */
    public function parse(Request $request): MethodCallDTO
    {
        $data = json_decode($request->getContent(), true);

        $jsonrpc = $data['jsonrpc'] ?? '';
        $rpcMethod = $data['method'] ?? '';
        $params = $data['params'] ?? null;
        $id = $data['id'] ?? null;

        if ($jsonrpc !== '2.0' || $rpcMethod === '') {
            throw InvalidRequestException::fromBody();
        }

        return new MethodCallDTO(
            $jsonrpc,
            $rpcMethod,
            $params,
            $id
        );
    }
}
