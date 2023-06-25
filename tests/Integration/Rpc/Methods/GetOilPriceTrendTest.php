<?php

namespace Integration\Rpc\Methods;

use App\Lib\MongoDB\Client;
use App\Repository\OilPriceTrendRepository;
use App\Rpc\Methods\DTO\GetOilPriceTrendDTO;
use App\Rpc\Methods\GetOilPriceTrend;
use App\Service\OilPriceTrendService;
use App\Transformer\OilPriceTrendTransformer;
use MongoDB\Driver\Exception\Exception;
use PHPUnit\Framework\TestCase;

class GetOilPriceTrendTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testHappyFlow()
    {
        $mongoDbMock = $this->createMock(Client::class);
        $mongoDbMock->method('executeQuery')->willReturn(
            [
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
        $expected = [
            [
                'price' => 23.95,
                'dateISO8601' => '2000-01-04'
            ],
            [
                'price' => 23.72,
                'dateISO8601' => '2000-01-05'
            ],
        ];

        $method = new GetOilPriceTrend(
            new OilPriceTrendTransformer(),
            new OilPriceTrendService(
                new OilPriceTrendRepository(
                    $mongoDbMock
                )
            )
        );

        $dto = new GetOilPriceTrendDTO(
            new \DateTime(),
            new \DateTime(),
        );

        $this->assertEquals(
            ['prices' => $expected],
            ($method)($dto)
        );
    }
}
