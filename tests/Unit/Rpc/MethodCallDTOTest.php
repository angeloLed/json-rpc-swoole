<?php

namespace App\Rpc;

use App\Lib\Exceptions\InvalidRequestException;
use App\Lib\ServiceContainer\ServiceContainer;
use App\Rpc\MethodCallDTO;
use App\Rpc\Methods\GetOilPriceTrend;
use App\Rpc\RequestParser\GetRequestParser;
use App\Service\OilPriceTrendService;
use App\Transformer\OilPriceTrendTransformer;
use PHPUnit\Framework\MockObject\Rule\InvocationOrder;
use PHPUnit\Framework\MockObject\Rule\InvokedAtIndex;
use PHPUnit\Framework\TestCase;
use Swoole\Http\Request;

class MethodCallDTOTest extends TestCase
{
    public function testBuilder()
    {
        $serviceContainer = $this->createMock(ServiceContainer::class);

        $serviceContainer
            ->expects(new InvokedAtIndex(0))
            ->method('get')
            ->with(OilPriceTrendTransformer::class)
            ->willReturn(
                $this->createMock(OilPriceTrendTransformer::class)
            )
        ;

        $serviceContainer
            ->expects(new InvokedAtIndex(1))
            ->method('get')
            ->with(OilPriceTrendService::class)
            ->willReturn(
                $this->createMock(OilPriceTrendService::class)
            )
            ;

        $serviceContainer
            ->expects(new InvokedAtIndex(2))
            ->method('set')
            ->with(GetOilPriceTrend::getMethodName())
        ;

        MethodsBuilder::build($serviceContainer);
    }
}
