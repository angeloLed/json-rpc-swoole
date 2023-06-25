<?php

namespace App\Rpc\RequestParser;

use App\Rpc\MethodCallDTO;
use Swoole\Http\Request;

class ChainRequestParser implements RequestParserInterface
{
    /**
     * @param array<string, RequestParserInterface> $requestParserMap
     */
    public function __construct(
        private readonly array $requestParserMap
    ) {
    }

    public function parse(Request $request): MethodCallDTO
    {
        //parse request
        return $request->getMethod() === 'GET' ?
            ($this->requestParserMap['get'])->parse($request)
            :
            ($this->requestParserMap['post'])->parse($request);
    }
}
