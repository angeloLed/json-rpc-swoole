<?php

namespace App\Rpc\Methods;

use App\Lib\Exceptions\InvalidRpcArgumentsException;
use App\Rpc\Methods\DTO\GetOilPriceTrendDTO;
use App\Service\OilPriceTrendService;
use App\Transformer\OilPriceTrendTransformer;
use PHPUnit\Framework\TestCase;

class GetOilPriceTrendTest extends TestCase
{
    private GetOilPriceTrend $rpcMethod;

    private OilPriceTrendService $serviceMock;

    private OilPriceTrendTransformer $trasformerMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->rpcMethod = new GetOilPriceTrend(
            $this->trasformerMock = $this->createMock(OilPriceTrendTransformer::class),
            $this->createMock(OilPriceTrendService::class)
        );
    }

    public function testMissingStarDateParameter()
    {
        $this->expectException(InvalidRpcArgumentsException::class);

        $this->rpcMethod->parseRpcArguments(['endDateISO8601' => '2010-01-01']);
    }

    public function testMissingEndDateParameter()
    {
        $this->expectException(InvalidRpcArgumentsException::class);

        $this->rpcMethod->parseRpcArguments(['startDateISO8601' => '2010-01-01']);
    }

    public function testMalformedStarDate()
    {
        $this->expectException(InvalidRpcArgumentsException::class);

        $this->rpcMethod->parseRpcArguments([
            'startDateISO8601' => 'invalid-data',
            'endDateISO8601' => '2010-01-01'
        ]);
    }

    public function testMalformedEndDate()
    {
        $this->expectException(InvalidRpcArgumentsException::class);

        $this->rpcMethod->parseRpcArguments([
            'startDateISO8601' => '2010-01-01',
            'endDateISO8601' => 'invalid-data'
        ]);
    }

    public function testCorrectParseArguments()
    {
        $result = $this->rpcMethod->parseRpcArguments([
            'startDateISO8601' => '2010-01-01',
            'endDateISO8601' => '2010-01-02',
        ]);

        $this->assertEquals(
            new \DateTime('2010-01-01'),
            $result->statDate
        );
        $this->assertEquals(
            new \DateTime('2010-01-02'),
            $result->endDate
        );
    }

    public function testCorrectInvokation()
    {
        /*
         * NOTE: use ReflectionClass due to avoid PHP 8.2 readOnly property :/
         */
        $dtoMock = $this->getMockBuilder(GetOilPriceTrendDTO::class)
            ->disableOriginalConstructor()
            ->getMock();
        $classReflection = new \ReflectionClass(GetOilPriceTrendDTO::class);
        $reflectionProperty = $classReflection->getProperty('statDate');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($dtoMock, new \DateTime('2010-01-01'));
        $reflectionProperty = $classReflection->getProperty('endDate');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($dtoMock, new \DateTime('2010-01-02'));

        $this->trasformerMock->method('transformMany')->willReturn(
            $dataSet = [
                [
                    '_id' => ['$oid' => '6494168b0eab7ba90ca0a895'],
                    'Brent Spot Price' => 23.95,
                    'Date' => '2000-01-04'
                ],
                [
                    '_id' => ['$oid' => '6494168b0eab7ba90ca0a896'],
                    'Brent Spot Price' => 23.72,
                    'Date' => '2000-01-05'
                ]
            ]
        );

        $this->assertEquals(
            ['prices' => $dataSet],
            $this->rpcMethod->__invoke($dtoMock)
        );

    }
}