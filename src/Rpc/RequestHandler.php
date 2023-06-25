<?php

namespace App\Rpc;

use App\Lib\Exceptions\InvalidRequestException;
use App\Lib\Exceptions\NotFoundException;
use App\Lib\Exceptions\RpcMethodException;
use App\Lib\ServiceContainer\ServiceContainerInterface;
use App\Rpc\Methods\AbstractRpcMethod;
use App\Rpc\RequestParser\RequestParserInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Swoole\Http\Request;

class RequestHandler
{
    private const HEADER_CONTENT_TYPE = 'content-type';

    public function __construct(
        private readonly ServiceContainerInterface $serviceContainer,
        private readonly RequestParserInterface    $requestParser,
    ) {
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws InvalidRequestException
     * @throws ContainerExceptionInterface
     * @throws RpcMethodException
     * @throws NotFoundException
     */
    public function handle(Request $request): array
    {
        //validate request type
        if (
            array_key_exists(self::HEADER_CONTENT_TYPE, $request->header) &&
            !in_array($request->header[self::HEADER_CONTENT_TYPE], ['application/json-rpc', 'application/json', 'application/jsonrequest'], true)) {
            throw InvalidRequestException::fromContentType($request->header[self::HEADER_CONTENT_TYPE]); //400
        }

        // validate Method Request
        if (!in_array($request->getMethod(), ['GET', 'POST'], true)) {
            throw InvalidRequestException::fromHttpMethod($request->getMethod()); // http - 400
        }

        // generate Rpc call information
        $dto = $this->requestParser->parse($request);

        // execute RPC Call
        $result = $this->executeRpcMethod($dto);

        // response
        return [
            'jsonrpc' => '2.0',
            ...(
                $dto->id !== null ?
                ['id' => $dto->id, 'result' => $result]
                :
                []
            ),
        ];
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws NotFoundException
     * @throws RpcMethodException
     */
    private function executeRpcMethod(MethodCallDTO $rpcRequestDTO): mixed
    {
        if (!$this->serviceContainer->has($rpcRequestDTO->method)) {
            throw NotFoundException::fromRpcMethod($rpcRequestDTO->method); // http - 404
        }

        $rpcMethod = $this->serviceContainer->get($rpcRequestDTO->method);

        if (!$rpcMethod instanceof AbstractRpcMethod) {
            throw RpcMethodException::fromMethod($rpcRequestDTO->method); // http - 500
        }

        $methodDto = $rpcMethod->parseRpcArguments($rpcRequestDTO->params ?? []);

        $result = ($rpcMethod)($methodDto);

        return $result;
    }

}
