<?php

namespace App;

use App\Lib\Exceptions\InvalidRequestException;
use App\Lib\Exceptions\NotFoundException;
use App\Lib\Exceptions\RpcMethodException;
use App\Lib\ServiceContainer\ServiceContainerInterface;
use App\Rpc\RequestHandler;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

class App
{
    private const HEADER_CONTENT_TYPE = 'content-type';

    public function __construct(
        private readonly array            $envs,
        private readonly LoggerInterface  $logger,
        private ServiceContainerInterface $serviceContainer,
        private readonly RequestHandler   $rpcRequestHandler,
    ) {
    }

    public function run(): void
    {
        $this->logger->log(LogLevel::INFO, "Init Swoole HTTP server");
        $port = array_keys($this->envs, 'port') ? (int)$this->envs['port'] : 9501;
        $host = array_keys($this->envs, 'host') ? (int)$this->envs['host'] : '0.0.0.0';

        $server = new Server($host, $port);
        $server->set([
            'worker_num' => 1,
            'enable_coroutine' => true, // required for mongodb
        ]);

        $server->on("Start", function (Server $server) use ($port, $host) {
            $this->logger->log(
                LogLevel::INFO,
                sprintf("Swoole HTTP server is started at http://%s:%d", $host, $port)
            );
        });

        $server->on("Request", function (Request $request, Response $response) {
            try {
                $this->requestHandle($request, $response);
            } catch (\Throwable $exception) {
                $this->responseError($exception, $response);
            }
        });

        $server->start();
    }

    private function responseError(\Throwable $exception, Response $response): void
    {
        $this->logger->log(LogLevel::ERROR, $exception->getMessage());

        $httpStatusCode = match (get_class($exception)) {
            InvalidRequestException::class => 400,
            NotFoundException::class => 404,
            default => 500,
        };

        $response->setStatusCode($httpStatusCode);
        $response->header(self::HEADER_CONTENT_TYPE, 'application/json');
        $response->end(
            json_encode([
                'jsonrpc' => '2.0',
                'error' => [
                    'code' => ($exception->getCode() !== 0) ? $exception->getCode() : -32603,
                    'message' => $exception->getMessage(),
                ],
                'id' => null
            ])
        );
    }

    /**
     * @throws RpcMethodException
     * @throws InvalidRequestException
     */
    private function requestHandle(Request $request, Response $response): void
    {
        $this->logger->log(
            LogLevel::DEBUG,
            'request',
            [
                self::HEADER_CONTENT_TYPE => $request->header[self::HEADER_CONTENT_TYPE] ?? null,
                'method' => $request->getMethod()
            ]
        );

        // rpc entrypoint/controller
        $body = $this->rpcRequestHandler->handle($request);

        if (!array_key_exists('id', $body)) {
            $response->setStatusCode('204');
            $response->end('');
            return;
        }

        $response->header(self::HEADER_CONTENT_TYPE, $request->header[self::HEADER_CONTENT_TYPE]);
        $response->end(json_encode($body));
    }
}
