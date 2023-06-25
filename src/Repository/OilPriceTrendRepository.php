<?php

namespace App\Repository;

use App\Lib\MongoDB\Client;
use MongoDB\Driver\Exception\Exception;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;

class OilPriceTrendRepository
{
    private const COLLECTION_NAME = 'oil_price_trend';

    public function __construct(
        private readonly Client $mongoClient
    ) {
    }

    /**
     * @throws Exception
     */
    public function getByRangeDate(\DateTime $startDate, \DateTime $endDate): array
    {
        $query = new Query(['Date' => ['$gte' => $startDate->format('c'), '$lt' => $endDate->format('c')]]);
        return $this->mongoClient->executeQuery(self::COLLECTION_NAME, $query);
    }
}
