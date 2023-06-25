<?php

namespace App\Rpc\Methods;

use App\Lib\Exceptions\InvalidRpcArgumentsException;
use App\Rpc\Methods\DTO\GetOilPriceTrendDTO;
use App\Service\OilPriceTrendService;
use App\Transformer\TransformerInterface;
use MongoDB\Driver\Exception\Exception;

class GetOilPriceTrend extends AbstractRpcMethod
{
    public function __construct(
        private readonly TransformerInterface $oilPriceTrendTransformer,
        private readonly OilPriceTrendService $oilService,
    ) {

    }

    /**
     * @throws Exception
     */
    public function __invoke(GetOilPriceTrendDTO $dto): array
    {
        $result = $this->oilService->getByRangeDate($dto->statDate, $dto->endDate);

        return [
            'prices' => $this->oilPriceTrendTransformer->transformMany($result)
        ];
    }

    /**
     * @throws \Exception
     */
    public function parseRpcArguments(array $arguments = []): GetOilPriceTrendDTO
    {
        if (
            !array_key_exists('startDateISO8601', $arguments) ||
            !array_key_exists('endDateISO8601', $arguments)
        ) {
            throw InvalidRpcArgumentsException::fromMethod(self::getMethodName());
        }

        try {
            $startDate = new \DateTime($arguments['startDateISO8601']);
            $endDate = new \DateTime($arguments['endDateISO8601']);
        } catch (\Throwable $x) {
            throw InvalidRpcArgumentsException::fromMethodAndTypeArguments(self::getMethodName(), $x);
        }

        return new GetOilPriceTrendDTO($startDate, $endDate);
    }

    public static function getMethodName(): string
    {
        return 'getOilPriceTrend';
    }
}
