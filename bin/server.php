<?php

use Psr\Log\LogLevel;

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Load environments
$dotenv = \Dotenv\Dotenv::createUnsafeImmutable(dirname(__DIR__));
$dotenv->load();

// Create a log channel
$logger = new \Monolog\Logger('default');
$logger->pushHandler(
    new \Monolog\Handler\StreamHandler(
        'php://output',
        getenv('DEBUG') === 'true' ? \Monolog\Level::Debug : \Monolog\Level::Warning
    )
);

//Build service container
$logger->log(LogLevel::INFO, "Build service container");
$mongoDBConnectionManager = new \MongoDB\Driver\Manager(getenv('MONGODB_CONNECTION'));
$serviceContainer = new \App\Lib\ServiceContainer\ServiceContainer();
$serviceContainer->set(App\Lib\MongoDB\Client::class, new App\Lib\MongoDB\Client(getenv('MONGODB_DATABASE'), $mongoDBConnectionManager));
$serviceContainer->set(
    \App\Repository\OilPriceTrendRepository::class,
    new \App\Repository\OilPriceTrendRepository($serviceContainer->get(App\Lib\MongoDB\Client::class))
);
$serviceContainer->set(
    \App\Service\OilPriceTrendService::class,
    new \App\Service\OilPriceTrendService($serviceContainer->get(\App\Repository\OilPriceTrendRepository::class))
);
$serviceContainer->set(
    \App\Transformer\OilPriceTrendTransformer::class,
    new \App\Transformer\OilPriceTrendTransformer()
);

// build rpc methods
$logger->log(LogLevel::INFO, "Load RPC Methods");
\App\Rpc\MethodsBuilder::build($serviceContainer);

// Create and run application
$application = new \App\App(
    getenv(),
    $logger,
    $serviceContainer,
    new \App\Rpc\RequestHandler(
        $serviceContainer,
        new \App\Rpc\RequestParser\ChainRequestParser(
            [
                'get' => new \App\Rpc\RequestParser\GetRequestParser(),
                'post' => new \App\Rpc\RequestParser\PostRequestParser()
            ]
        )
    )
);

// Run application
$application->run();
