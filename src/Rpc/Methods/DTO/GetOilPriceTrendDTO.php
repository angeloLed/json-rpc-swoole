<?php

namespace App\Rpc\Methods\DTO;

class GetOilPriceTrendDTO
{
    public function __construct(
        public readonly \DateTime $statDate,
        public readonly \DateTime $endDate
    ) {
    }
}
