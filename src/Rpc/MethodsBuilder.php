<?php

namespace App\Rpc;

use App\Lib\ServiceContainer\ServiceContainer;
use App\Rpc\Methods\GetOilPriceTrend;
use App\Rpc\Methods\OtherMethod;
use App\Service\OilPriceTrendService;
use App\Transformer\OilPriceTrendTransformer;

class MethodsBuilder
{
    public static function build(ServiceContainer $serviceContainer)
    {
        $serviceContainer->set(
            GetOilPriceTrend::getMethodName(),
            new GetOilPriceTrend(
                $serviceContainer->get(OilPriceTrendTransformer::class),
                $serviceContainer->get(OilPriceTrendService::class)
            )
        );
        $serviceContainer->set(OtherMethod::getMethodName(), new OtherMethod());
        $serviceContainer->set('wrongService', new class () {}); // for test
    }
}
