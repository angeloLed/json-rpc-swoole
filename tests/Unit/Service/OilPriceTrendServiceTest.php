<?php

namespace App\Service;

use App\Repository\OilPriceTrendRepository;
use PHPUnit\Framework\TestCase;

class OilPriceTrendServiceTest extends TestCase
{
    public function testGetListByDates()
    {
        $repositoryMock = $this->createMock(OilPriceTrendRepository::class);
        $repositoryMock->method('getByRangeDate')->willReturn(
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

        $service = new OilPriceTrendService(
            $repositoryMock
        );

        $this->assertEquals(
            $expectedResult,
            $service->getByRangeDate(
                new \DateTime('2010-01-01'),
                new \DateTime('2010-01-02')
            )
        );

    }
}