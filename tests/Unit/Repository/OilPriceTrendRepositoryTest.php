<?php

namespace App\Repository;

use App\Lib\MongoDB\Client;
use PHPUnit\Framework\TestCase;

class OilPriceTrendRepositoryTest extends TestCase
{
    public function testGetListByDates()
    {
        $mongoDbMock = $this->createMock(Client::class);
        $mongoDbMock->method('executeQuery')->willReturn(
            $expectedResult = [
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

        $repository = new OilPriceTrendRepository($mongoDbMock);

        $this->assertEquals(
            $expectedResult,
            $repository->getByRangeDate(
                new \DateTime('2010-01-01'),
                new \DateTime('2010-01-02')
            )
        );
    }
}