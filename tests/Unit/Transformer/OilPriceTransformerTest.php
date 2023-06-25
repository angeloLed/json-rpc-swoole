<?php

namespace App\Transformer;

use App\Repository\OilPriceTrendRepository;
use App\Service\OilPriceTrendService;
use PHPUnit\Framework\TestCase;

class OilPriceTransformerTest extends TestCase
{
    private OilPriceTrendTransformer $transformer;

    public function setUp(): void
    {
        $this->transformer = new OilPriceTrendTransformer();
    }

    public function testSingleTransformer()
    {
        $result = $this->transformer->transform(
            [
                '_id' => ['$oid' => '649475def2eb3305b1c47dba'],
                'Brent Spot Price' => 23.95,
                'Date' => '2000-01-04'
            ]
        );
        $expected = [
            'price' => 23.95,
            'dateISO8601' => '2000-01-04'
        ];

        $this->assertEquals(
            $expected,
            $result
        );
    }

    public function testManyTransformer()
    {
        $result = $this->transformer->transformMany(
            [
                [
                    '_id' => ['$oid' => '649475def2eb3305b1c47dba'],
                    'Brent Spot Price' => 23.95,
                    'Date' => '2000-01-04'
                ],
                [
                    '_id' => ['$oid' => '649475def2eb3305b1c47dbb'],
                    'Brent Spot Price' => 10,
                    'Date' => '2001-01-04'
                ]
            ]
        );
        $expected = [
            [
                'price' => 23.95,
                'dateISO8601' => '2000-01-04'
            ],
            [
                'price' => 10,
                'dateISO8601' => '2001-01-04'
            ],
        ];

        $this->assertEquals(
            $expected,
            $result
        );
    }
}