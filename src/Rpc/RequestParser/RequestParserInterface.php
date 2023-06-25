<?php

namespace App\Rpc\RequestParser;

use App\Rpc\MethodCallDTO;
use Swoole\Http\Request;

interface RequestParserInterface
{
    public function parse(Request $request): MethodCallDTO;
}
