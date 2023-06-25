<?php

namespace App\Service;

use App\Repository\OilPriceTrendRepository;
use MongoDB\Driver\Exception\Exception;

class OilPriceTrendService
{
    public function __construct(
        private readonly OilPriceTrendRepository $repository
    ) {
    }

    /**
     * @throws Exception
     */
    public function getByRangeDate(\DateTime $startDate, \DateTime $endDate): array
    {
        return $this->repository->getByRangeDate($startDate, $endDate);
    }
}
